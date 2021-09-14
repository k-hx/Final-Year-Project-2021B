<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use DB;
use App\Models\EmployeeLeave;
use App\Models\Employee;
use Carbon\Carbon;

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
          Session::flash('info',"Employee leave record for current year is already existed!");
       }

       return redirect()->route('allEmployeesLeaveGrade');
       // return view('debuggingView')->with('isSuccess',$isSuccess);
  }


  public function showEmployeeOwnLeave() {
     $employees=DB::table('employees')
     ->leftjoin('leave_grades','leave_grades.id','=','employees.leave_grade')
     ->select('leave_grades.name as leaveGradeName', 'employees.*')
     ->where('employees.id','=',Auth::id())
     ->get();

     $employeeLeaves=DB::table('employee_leaves')
     ->where('employee','=',Auth::id())
     ->get();

     return view('ownLeaveGrade')
     ->with('employees',$employees)
     ->with('employeeLeaves',$employeeLeaves);
  }
}
