<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use DB;
Use Auth;
use Carbon\Carbon;
use App\Models\EmployeeLeave;
use App\Models\Employee;

class EmployeeLeaveController extends Controller
{
   public function __construct() {
      $this->middleware('auth');
   }

   public function createLeaveRecord() {
      $isSuccess=0;

      $employeeLeaves=DB::table('employee_leaves')
                     ->where('year','=',Carbon::now()->format('Y'))
                     ->get();

      if($employeeLeaves->isNotEmpty()) {
         $isSuccess=2;
      } else {
         $isSuccess=1;
         $employees=DB::table('employees')
                     ->where('status','=','ACTIVE')
                     ->get();

         $previousEmployeeLeaves=DB::table('employee_leaves')
                                 ->where('year','!=',Carbon::now()->format('Y'))
                                 ->get();

         foreach($previousEmployeeLeaves as $previousEmployeeLeave) {
            $previousEmployeeLeave=EmployeeLeave::find($previousEmployeeLeave->id);
            $previousEmployeeLeave->status='Invalid';
            $previousEmployeeLeave->save();
         }

         foreach($employees as $employee) {
            $leaveEntitlements=DB::table('leave_entitlements')
                              ->where('leaveGrade','=',$employee->leave_grade)
                              ->get();

            foreach($leaveEntitlements as $leaveEntitlement) {
               //create employee leave record
               $createEmployeeLeave=EmployeeLeave::create([
                  'employee'=>$employee->id,
                  'leave_type'=>$leaveEntitlement->leaveType,
                  'total_days'=>$leaveEntitlement->num_of_days,
                  'leaves_taken'=>0,
                  'remaining_days'=>$leaveEntitlement->num_of_days,
                  'year'=>Carbon::now()->format('Y'),
                  'status'=>'Valid',
               ]);
            }
         }
      }

       if($isSuccess === 1) {
          Session::flash('success',"Employee leave record for current year is created successfully!");
       } else if($isSuccess = 2) {
          Session::flash('primary',"Employee leave record for current year is already existed!");
       }

       return redirect()->route('allEmployeesLeaveGrade');
  }

  public function showAnEmployeesLeave($id) {
     $employees=DB::table('employees')
     ->leftjoin('leave_grades','leave_grades.id','=','employees.leave_grade')
     ->select('leave_grades.name as leaveGradeName', 'employees.*')
     ->where('employees.id','=',$id)
     ->get();

     foreach($employees as $employee) {
        $employee=Employee::find($employee->id);
        $supervisor=$employee->supervisor;
     }

     if($supervisor == Auth::id()) {
        $employeeLeaves=DB::table('employee_leaves')
       ->leftjoin('leave_types','leave_types.id','=','employee_leaves.leave_type')
       ->select('leave_types.name as leaveTypeName','employee_leaves.*')
       ->orderBy('leave_types.id','asc')
       ->where('employee_leaves.employee',$id)
       ->get();

       return view('admin/leave/employeesLeaveGrade')->with('employees',$employees)
       ->with('employeeLeaves',$employeeLeaves);
    } else {
      Session::flash('danger',"The employee is not under your supervision.");
      return redirect()->route('allEmployeesLeaveGrade');
   }
  }


  public function showEmployeeOwnLeave() {
     $employees=DB::table('employees')
     ->leftjoin('leave_grades','leave_grades.id','=','employees.leave_grade')
     ->select('leave_grades.name as leaveGradeName', 'employees.*')
     ->where('employees.id',Auth::id())
     ->get();

     $employeeLeaves=DB::table('employee_leaves')
     ->leftjoin('leave_types','leave_types.id','=','employee_leaves.leave_type')
     ->select('leave_types.name as leaveTypeName','employee_leaves.*')
     ->where('employee_leaves.employee',Auth::id())
     ->orderBy('leave_type','asc')
     ->get();

     return view('employee/leave/ownLeaveGrade')
     ->with('employees',$employees)
     ->with('employeeLeaves',$employeeLeaves);
  }
}
