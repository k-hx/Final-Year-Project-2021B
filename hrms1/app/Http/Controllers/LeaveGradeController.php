<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\LeaveGrade;
use App\Models\Employee;
use App\Models\EmployeeLeave;
use App\Models\LeaveGradeHistory;
use App\Models\LeaveEntitlement;
use Session;
use Carbon\Carbon;

class LeaveGradeController extends Controller
{
   public function __construct() {
        $this->middleware('auth');
   }

    public function store() {
      $r=request();
      $addLeaveGrade=LeaveGrade::create([
         'name'=>$r->name,
         'status'=>'Added',
      ]);

      Session::flash('success',"Leave grade created successfully!");
      return redirect()->route('showLeaveGrades');
   }

   public function show() {
      $leaveGrades=DB::table('leave_grades')
                     ->where('status','=','Added')
                     ->orWhere('status','=','Edited')
                     ->orderBy('id','asc')
                     ->get();

      $employees=Employee::all();

      return view('showLeaveGrades')->with('leaveGrades',$leaveGrades)
                                    ->with('employees',$employees);
   }

   public function edit($id) {
      $leaveGrades=LeaveGrade::all()->where('id',$id);
      return view('editLeaveGradeName')->with('leaveGrades',$leaveGrades);
   }

   public function update() {
      $r=request();
      $leaveGrades=LeaveGrade::find($r->id);

      $leaveGrades->name=$r->name;
      $leaveGrades->status='Edited';
      $leaveGrades->save();

      Session::flash('success',"Leave grade updated successfully!");
      return redirect()->route('showLeaveGrades');
   }

   public function delete($id) {
      $leaveGrades=LeaveGrade::find($id);

      $leaveGrades->status='Deleted';
      $leaveGrades->save();

      $employees=Employee::all()->where('leave_grade',$id);
      foreach($employees as $employee) {
         $employee->leave_grade='Unassigned';
         $employee->save();
      }

      $leaveGradeHistories=LeaveGradeHistory::all()->where('leave_grade',$id);
      foreach($leaveGradeHistories as $leaveGradeHistory) {
         $leaveGradeHistory->effective_until=Carbon::now();
         $leaveGradeHistory->save();
      }

      //update employees' leave record

      return redirect()->route('showLeaveGrades');
   }

   public function showAllEmployeesLeaveGrade() {
      $employees=DB::table('employees')
                  ->leftjoin('leave_grades','leave_grades.id','=','employees.leave_grade')
                  ->select('leave_grades.name as leaveGradeName', 'employees.*')
                  ->orderBy('id','asc')
                  ->get();

      return view('allEmployeesLeaveGrade')->with('employees',$employees);
   }

   public function showAnEmployeesLeave($id,$leaveGradeId) {
      $employees=DB::table('employees')
                  ->leftjoin('leave_grades','leave_grades.id','=','employees.leave_grade')
                  ->select('leave_grades.name as leaveGradeName', 'employees.*')
                  ->where('employees.id','=',$id)
                  ->get();

      $leaveEntitlements=DB::table('leave_entitlements')
                  ->leftjoin('leave_types','leave_entitlements.leaveType','=','leave_types.id')
                  ->select('leave_types.id as leaveTypeId','leave_types.name as leaveTypeName','leave_entitlements.*')
                  ->orderBy('leave_types.id','asc')
                  ->where('leave_entitlements.leaveGrade','=',$leaveGradeId)
                  ->get();

      return view('employeesLeaveGrade')->with('employees',$employees)
                                       ->with('leaveEntitlements',$leaveEntitlements);
   }

   public function setEmployeesLeaveGradePage($id) {
      $employees=Employee::all()->where('id',$id);
      $leaveGrades=DB::table('leave_grades')
                  ->orderBy('name','asc')
                  ->get();

      return view('setEmployeesLeaveGrade')->with('employees',$employees)
                                          ->with('leaveGrades',$leaveGrades);
   }

   public function updateEmployeesLeaveGrade() {
      $r=request();

      $id=$r->employee;
      $employees=Employee::find($id);
      $employees->leave_grade=$r->leave_grade;
      $employees->save();

      if($r->originalLeaveGrade !== 'Unassigned') {
         $lastLeaveGradeHistories=DB::table('leave_grade_histories')
                                       ->where('employee','=',$id)
                                       ->whereNull('effective_until')
                                       ->get();

         foreach($lastLeaveGradeHistories as $lastLeaveGradeHistory) {
            $id=$lastLeaveGradeHistory->id;
            $lastLeaveGradeHistory=LeaveGradeHistory::find($id);
            $lastLeaveGradeHistory->effective_until=Carbon::now();
            $lastLeaveGradeHistory->save();
         }
      }

      $createLeaveGradeHistory=LeaveGradeHistory::create([
         'employee'=>$r->employee,
         'leave_grade'=>$r->leave_grade,
         'effective_from'=>Carbon::now(),
      ]);


      if($r->originalLeaveGrade == 'Unassigned') {
         $leaveEntitlements=DB::table('leave_entitlements')
                     ->leftjoin('leave_types','leave_entitlements.leaveType','=','leave_types.id')
                     ->select('leave_types.id as leaveTypeId','leave_types.name as leaveTypeName','leave_entitlements.*')
                     ->orderBy('leave_types.id','asc')
                     ->where('leave_entitlements.leaveGrade','=',$r->leave_grade)
                     ->get();

         foreach ($leaveEntitlements as $leaveEntitlement) {
            $createEmployeeLeave=EmployeeLeave::create([
               'employee'=>$r->employee,
               'leave_type'=>$leaveEntitlement->leaveType,
               'total_days'=>$leaveEntitlement->num_of_days,
               'leaves_taken'=>0,
               'remaining_days'=>$leaveEntitlement->num_of_days,
               'year'=>Carbon::now()->format('Y'),
            ]);
         }
      } else {
         $leaveGradeId=$r->leave_grade;
         $leaveEntitlements=DB::table('leave_entitlements')
                     ->leftjoin('leave_types','leave_entitlements.leaveType','=','leave_types.id')
                     ->select('leave_types.id as leaveTypeId','leave_types.name as leaveTypeName','leave_entitlements.*')
                     ->orderBy('leave_types.id','asc')
                     ->where('leave_entitlements.leaveGrade','=',$leaveGradeId)
                     ->get();

         $employeeLeaves=DB::table('employee_leaves')
                        ->where('employee','=',$r->employee)
                        ->where('year','=',Carbon::now()->format('Y'))
                        ->get();

         foreach($leaveEntitlements as $leaveEntitlement) {
            $leaveEntitlementId=$leaveEntitlement->id;
            $leaveEntitlement=LeaveEntitlement::find($leaveEntitlementId);
            $isMatched=0;

            foreach($employeeLeaves as $employeeLeave) {
               $employeeLeaveId=$employeeLeave->id;
               $employeeLeave=EmployeeLeave::find($employeeLeaveId);

               if($employeeLeave->leave_type == $leaveEntitlement->leaveType) {
                  $isMatched=1;

                  $employeeLeave->total_days=$leaveEntitlement->num_of_days;
                  $remaining_days=($leaveEntitlement->num_of_days)-($employeeLeave->leaves_taken);
                  if($leaveEntitlement->num_of_days-$employeeLeave->leaves_taken <0) {
                     $remaining_days=0;
                  }
                  $employeeLeave->remaining_days=$remaining_days;
               }
               $employeeLeave->save();
            }

            // if($isMatched==0) {
            //    $leaveEntitlements=DB::table('leave_entitlements')
            //                ->leftjoin('leave_types','leave_entitlements.leaveType','=','leave_types.id')
            //                ->select('leave_types.id as leaveTypeId','leave_types.name as leaveTypeName','leave_entitlements.*')
            //                ->orderBy('leave_types.id','asc')
            //                ->where('leave_entitlements.leaveGrade','=',$r->leave_grade)
            //                ->get();
            //
            //    foreach ($leaveEntitlements as $leaveEntitlement) {
            //       $createEmployeeLeave=EmployeeLeave::create([
            //          'employee'=>$r->employee,
            //          'leave_type'=>$leaveEntitlement->leaveType,
            //          'total_days'=>$leaveEntitlement->num_of_days,
            //          'leaves_taken'=>0,
            //          'remaining_days'=>$leaveEntitlement->num_of_days,
            //          'year'=>Carbon::now()->format('Y'),
            //       ]);
            //    }
            // }

         }
      }

      Session::flash('success',"Leave grade assigned successfully!");
      return redirect()->route('allEmployeesLeaveGrade');
   }

   function debuggingFunction() {
      $leaveGradeId=7;
      $leaveEntitlements=DB::table('leave_entitlements')
                  ->leftjoin('leave_types','leave_entitlements.leaveType','=','leave_types.id')
                  ->select('leave_types.id as leaveTypeId','leave_types.name as leaveTypeName','leave_entitlements.*')
                  ->orderBy('leave_types.id','asc')
                  ->where('leave_entitlements.leaveGrade','=',$leaveGradeId)
                  ->get();

      $employeeLeaves=DB::table('employee_leaves')
                     ->where('employee','=',1)
                     ->where('year','=',Carbon::now()->format('Y'))
                     ->get();


      return view('debuggingView')->with('leaveEntitlements',$leaveEntitlements)
                                 ->with('employeeLeaves',$employeeLeaves);

   }

}
