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
use Auth;

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

      return view('admin/showLeaveGrades')->with('leaveGrades',$leaveGrades)
      ->with('employees',$employees);
   }

   public function edit($id) {
      $leaveGrades=LeaveGrade::all()->where('id',$id);
      return view('admin/editLeaveGradeName')->with('leaveGrades',$leaveGrades);
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

      Session::flash('success',"Leave grade deleted successfully!");
      return redirect()->route('showLeaveGrades');
   }

   public function showAllEmployeesLeaveGrade() {
      $employees=DB::table('employees')
      ->leftjoin('leave_grades','leave_grades.id','=','employees.leave_grade')
      ->select('leave_grades.name as leaveGradeName', 'employees.*')
      ->where('employees.status','=','ACTIVE')
      ->where('employees.supervisor','=',Auth::id())
      ->orderBy('id','asc')
      ->get();

      return view('admin/allEmployeesLeaveGrade')->with('employees',$employees);
   }

   public function setEmployeesLeaveGradePage($id) {
      $employees=Employee::all()->where('id',$id);
      $leaveGrades=DB::table('leave_grades')
      ->orderBy('name','asc')
      ->get();

      return view('admin/setEmployeesLeaveGrade')->with('employees',$employees)
      ->with('leaveGrades',$leaveGrades);
   }

   public function updateEmployeesLeaveGrade() {
      $r=request();

      $id=$r->employee;
      //update leave grade in employee table
      $employees=Employee::find($id);
      $employees->leave_grade=$r->leave_grade;
      $employees->save();

      //if employee was assigned with another leave grade before,
      //update the previous leave grade history "effective_until"
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

      //create a new leave grade history record
      $createLeaveGradeHistory=LeaveGradeHistory::create([
         'employee'=>$r->employee,
         'leave_grade'=>$r->leave_grade,
         'effective_from'=>Carbon::now(),
      ]);

      //if employee previous leave grade is unassigned,
      //create new employeeLeave record for each leave entitlement
      if($r->originalLeaveGrade == 'Unassigned') {
         $leaveEntitlements=DB::table('leave_entitlements')
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
               'status'=>'Valid',
            ]);
         }
      } else {
         //if employee was assigned with another leave grade before,
         //needs to update the previous records,
         //and add new record if the leave type is not provided before

         $leaveGradeId=$r->leave_grade;

         //retrieve leave entitlement for the new leave grade
         $leaveEntitlements=DB::table('leave_entitlements')
         ->where('leave_entitlements.leaveGrade','=',$leaveGradeId)
         ->get();

         //retrieve employee's leave record for present year
         $employeeLeaves=DB::table('employee_leaves')
         ->where('employee','=',$r->employee)
         ->where('year','=',Carbon::now()->format('Y'))
         ->get();

         //set isMatched leave entitlement with employee's leave record to 0(false)
         $isMatched=0;

         //for each leave entitlement for the leave grade ...
         foreach($leaveEntitlements as $leaveEntitlement) {
            //set the value of $isMatched to 0(false) for every loop
            $isMatched=0;

            //find each leave entitlement with its id
            $leaveEntitlementId=$leaveEntitlement->id;
            $leaveEntitlement=LeaveEntitlement::find($leaveEntitlementId);

            //need to see if the leave entitlement for the leave grade
            //so we need to loop evert employeeLeave records
            foreach($employeeLeaves as $employeeLeave) {
               //find each employeeLeave record with its id
               $employeeLeaveId=$employeeLeave->id;
               $employeeLeave=EmployeeLeave::find($employeeLeaveId);

               //if the employeeLeave leave type matched with the leave entitlement leave type
               if($employeeLeave->leave_type == $leaveEntitlement->leaveType) {
                  //set isMatched to 1(true)
                  $isMatched=1;

                  //update the data of the employee leave
                  $employeeLeave->total_days=$leaveEntitlement->num_of_days;
                  $remaining_days=($leaveEntitlement->num_of_days)-($employeeLeave->leaves_taken);
                  if($leaveEntitlement->num_of_days-$employeeLeave->leaves_taken <0) {
                     $remaining_days=0;
                  }
                  $employeeLeave->remaining_days=$remaining_days;
                  $employeeLeave->status='Valid';
                  $employeeLeave->save();
               }
            }

            //if match is not found after looping all employee，
            //we need to create a new employee leave record
            if($isMatched==0) {
               $createEmployeeLeave=EmployeeLeave::create([
                  'employee'=>$r->employee,
                  'leave_type'=>$leaveEntitlement->leaveType,
                  'total_days'=>$leaveEntitlement->num_of_days,
                  'leaves_taken'=>0,
                  'remaining_days'=>$leaveEntitlement->num_of_days,
                  'year'=>Carbon::now()->format('Y'),
                  'status'=>'Valid',
               ]);
            }
         }

         foreach($employeeLeaves as $employeeLeave) {
            //set isMatched leave entitlement with employee's leave record to 0(false)
            $isMatched=0;

            //find each employeeLeave record with its id
            $employeeLeaveId=$employeeLeave->id;
            $employeeLeave=EmployeeLeave::find($employeeLeaveId);

            foreach($leaveEntitlements as $leaveEntitlement) {
               //find each leave entitlement with its id
               $leaveEntitlementId=$leaveEntitlement->id;
               $leaveEntitlement=LeaveEntitlement::find($leaveEntitlementId);

               if($employeeLeave->leave_type == $leaveEntitlement->leaveType) {
                  $isMatched=1;
               }
            }

            if($isMatched==0) {
               //because it loops for every employee leave record,
               //if the employee leave record does not match with any leave entitlement,
               //it means the leave type is not included in the new leave grade,
               //so it needs to be set to invalid
               $employeeLeave->status='Invalid';
               $employeeLeave->save();
            }
         }
      }

      Session::flash('success',"Leave grade assigned successfully!");
      return redirect()->route('allEmployeesLeaveGrade');
   }


   //temporary
   public function dismissal($id) {
      //find the employee's last leave grade history
      $lastLeaveGradeHistories=DB::table('leave_grade_histories')
      ->where('employee','=',$id)
      ->whereNull('effective_until')
      ->get();

      //update "effective_until" for the employee's last leave grade history
      foreach($lastLeaveGradeHistories as $lastLeaveGradeHistory) {
         $id=$lastLeaveGradeHistory->id;
         $lastLeaveGradeHistory=LeaveGradeHistory::find($id);
         $lastLeaveGradeHistory->effective_until=Carbon::now();
         $lastLeaveGradeHistory->save();
      }

      //find the employee's last leave records for present years
      $employeeLeaves=DB::table('employee_leaves')
      ->where('employee','=',$r->employee)
      ->where('year','=',Carbon::now()->format('Y'))
      ->get();

      foreach($employeeLeaves as $employeeLeave) {
         //find each employeeLeave record with its id
         $employeeLeaveId=$employeeLeave->id;
         $employeeLeave=EmployeeLeave::find($employeeLeaveId);

         if($employeeLeave->name!=="Annual Leave") {
            $employeeLeave->status='Invalid';
            $employeeLeave->save();
         }
      }
      //then on the day of end of service, set to invalid??
   }

}
