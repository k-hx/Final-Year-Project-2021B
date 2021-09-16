<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\LeaveType;
use App\Models\LeaveGrade;
use App\Models\Employee;
use App\Models\Admin;
use App\Models\EmployeeLeave;
use App\Models\AdminLeave;
use App\Models\LeaveEntitlement;
use Session;
use Carbon\Carbon;

class LeaveEntitlementController extends Controller
{
   public function __construct() {
      $this->middleware('auth');
   }

   public function show($id) {
      $leaveGrades=LeaveGrade::all()->where('id',$id);

      $currentEntitlements=DB::table('leave_entitlements')
      ->leftjoin('leave_types','leave_types.id','=','leave_entitlements.leaveType')
      ->select('leave_entitlements.leaveType as leaveTypeId','leave_types.name as leaveTypeName','leave_entitlements.*')
      ->where('leave_entitlements.leaveGrade','=',$id)
      ->orderBy('leave_types.id','asc')
      ->get();

      return view('admin/leave/leaveEntitlement')->with('leaveGrades',$leaveGrades)
      ->with('currentEntitlements',$currentEntitlements)
      ->with('leaveTypes',DB::table('leave_types')->orderBy('name','asc')->get());
   }

   public function addLeaveEntitlement($id) {
      //request data from the form
      $r=request();

      //create the leave entitlement in the leave entitlement table
      $addLeaveEntitlements=LeaveEntitlement::create([
         'leaveGrade'=>$r->id,
         'leaveType'=>$r->leaveType,
         'num_of_days'=>$r->num_of_days,
      ]);

      //update employee's leave ------------------------------------------------
      //find employees with the leave grade
      $employees=DB::table('employees')
      ->where('leave_grade',$r->id)      
      ->get();

      //for each employee who is assigned with the leave grade
      foreach($employees as $employee) {
         //find the employee with the employee id
         $employeeId=$employee->id;
         $employee=Employee::find($employeeId);

         //find their related leave record (same leave type, present year)
         $employeeLeaves=DB::table('employee_leaves')
         ->where('employee',$employee->id)
         ->where('leave_type',$r->leaveType)
         ->where('year',Carbon::now()->format('Y'))
         ->get();

         $number=0;
         foreach($employeeLeaves as $employeeLeave) {
            $number=$number+1;

            //find the employee leave with the employee leave id
            $employeeLeaveId=$employeeLeave->id;
            $employeeLeave=EmployeeLeave::find($employeeLeaveId);

            //update the record
            $employeeLeave->total_days=$r->num_of_days;
            $remaining_days=($r->num_of_days)-($employeeLeave->leaves_taken);
            if($remaining_days<0) {
               $remaining_days=0;
            }
            $employeeLeave->remaining_days=$remaining_days;
            $employeeLeave->status='Valid';
            $employeeLeave->save();
         }

         if($number===0) {
            //create employee leave record
            $createEmployeeLeave=EmployeeLeave::create([
               'employee'=>$employee->id,
               'leave_type'=>$r->leaveType,
               'total_days'=>$r->num_of_days,
               'leaves_taken'=>0,
               'remaining_days'=>$r->num_of_days,
               'year'=>Carbon::now()->format('Y'),
               'status'=>'Valid',
            ]);
         }
      }

      //update administrator's leave ------------------------------------------------
      //find administrators with the leave grade
      $admins=DB::table('admins')
      ->where('leave_grade',$r->id)
      ->get();

      //for each administrator who is assigned with the leave grade
      foreach($admins as $admin) {
         //find the employee with the employee id
         $adminId=$admin->id;
         $admin=Admin::find($adminId);

         //find their related leave record (same leave type, present year)
         $adminLeaves=DB::table('admin_leaves')
         ->where('admin',$admin->id)
         ->where('leave_type',$r->leaveType)
         ->where('year',Carbon::now()->format('Y'))
         ->get();

         $number=0;
         foreach($adminLeaves as $adminLeave) {
            $number=$number+1;

            //find the admin leave with the admin leave id
            $adminLeaveId=$adminLeave->id;
            $adminLeave=AdminLeave::find($adminLeaveId);

            //update the record
            $adminLeave->total_days=$r->num_of_days;
            $remaining_days=($r->num_of_days)-($adminLeave->leaves_taken);
            if($remaining_days<0) {
               $remaining_days=0;
            }
            $adminLeave->remaining_days=$remaining_days;
            $adminLeave->status='Valid';
            $adminLeave->save();
         }

         if($number===0) {
            //create admin leave record
            $createAdminLeave=AdminLeave::create([
               'admin'=>$admin->id,
               'leave_type'=>$r->leaveType,
               'total_days'=>$r->num_of_days,
               'leaves_taken'=>0,
               'remaining_days'=>$r->num_of_days,
               'year'=>Carbon::now()->format('Y'),
               'status'=>'Valid',
            ]);
         }
      }

      Session::flash('success',"Leave entitlement added successfully!");
      return redirect()->route('leaveEntitlement', ['id' => $id]);
   }


   public function edit($leaveGradeId,$id) {
      $leaveGrades=LeaveGrade::all()->where('id',$leaveGradeId);

      $currentEntitlements=DB::table('leave_entitlements')
      ->leftjoin('leave_types','leave_types.id','=','leave_entitlements.leaveType')
      ->select('leave_entitlements.leaveType as leaveTypeId','leave_types.name as leaveTypeName','leave_entitlements.*')
      ->where('leave_entitlements.leaveGrade','=',$leaveGradeId)
      ->get();

      $leaveEntitlements=DB::table('leave_entitlements')
      ->leftjoin('leave_types','leave_types.id','=','leave_entitlements.leaveType')
      ->select('leave_types.name as leaveTypeName','leave_entitlements.*')
      ->where('leave_entitlements.id','=',$id)
      ->get();

      return view('admin/leave/editLeaveEntitlement')->with('leaveGrades',$leaveGrades)
      ->with('leaveEntitlements',$leaveEntitlements)
      ->with('currentEntitlements',$currentEntitlements)
      ->with('leaveTypes',DB::table('leave_types')->orderBy('name','asc')->get());
   }

   public function updateLeaveEntitlement($leaveGradeId,$id) {
      $r=request();
      $leaveEntitlements=LeaveEntitlement::find($id);

      $leaveEntitlements->leaveType=$r->leaveType;
      $leaveEntitlements->num_of_days=$r->num_of_days;
      $leaveEntitlements->save();

      //update employee's leave record -----------------------------------------
      //find employees with the leave grade
      $employees=DB::table('employees')
      ->where('leave_grade',$leaveGradeId)
      ->get();

      //for each employee who is assigned with the leave grade
      foreach($employees as $employee) {
         //find the employee with the employee id
         $employeeId=$employee->id;
         $employee=Employee::find($employeeId);

         //find their related leave record (same leave type, present year)
         $employeeLeaves=DB::table('employee_leaves')
         ->where('employee',$employee->id)
         ->where('leave_type',$r->leaveType)
         ->where('year',Carbon::now()->format('Y'))
         ->get();

         foreach($employeeLeaves as $employeeLeave) {

            //find the employee leave with the employee leave id
            $employeeLeaveId=$employeeLeave->id;
            $employeeLeave=EmployeeLeave::find($employeeLeaveId);

            //update the record
            $employeeLeave->total_days=$r->num_of_days;
            $remaining_days=($r->num_of_days)-($employeeLeave->leaves_taken);
            if($remaining_days<0) {
               $remaining_days=0;
            }
            $employeeLeave->remaining_days=$remaining_days;
            $employeeLeave->status='Valid';
            $employeeLeave->save();
         }
      }

      //update administrator's leave record -----------------------------------------
      //find administrators with the leave grade
      $admins=DB::table('admins')
      ->where('leave_grade',$leaveGradeId)
      ->get();

      //for each employee who is assigned with the leave grade
      foreach($admins as $admin) {
         //find the administrator with the admin id
         $adminId=$admin->id;
         $admin=Admin::find($adminId);

         //find their related leave record (same leave type, present year)
         $adminLeaves=DB::table('admin_leaves')
         ->where('admin',$admin->id)
         ->where('leave_type',$r->leaveType)
         ->where('year',Carbon::now()->format('Y'))
         ->get();

         foreach($adminLeaves as $adminLeave) {

            //find the admin leave with the admin leave id
            $adminLeaveId=$adminLeave->id;
            $adminLeave=AdminLeave::find($admiinLeaveId);

            //update the record
            $adminLeave->total_days=$r->num_of_days;
            $remaining_days=($r->num_of_days)-($adminLeave->leaves_taken);
            if($remaining_days<0) {
               $remaining_days=0;
            }
            $adminLeave->remaining_days=$remaining_days;
            $adminLeave->status='Valid';
            $adminLeave->save();
         }
      }

      Session::flash('success',"Leave entitlement updated successfully!");
      return redirect()->route('leaveEntitlement',['id'=>$leaveGradeId]);
   }

   public function deleteLeaveEntitlement($leaveGradeId,$id) {
      $leaveEntitlements=LeaveEntitlement::find($id);

      //update employee's leave record -----------------------------------------
      //find employees with the leave grade
      $employees=DB::table('employees')
      ->where('leave_grade',$leaveGradeId)
      ->get();

      //for each employee who is assigned with the leave grade
      foreach($employees as $employee) {
         //find the employee with the employee id
         $employeeId=$employee->id;
         $employee=Employee::find($employeeId);

         //find their related leave record (same leave type, present year)
         $employeeLeaves=DB::table('employee_leaves')
         ->where('employee',$employee->id)
         ->where('leave_type',$leaveEntitlements->leaveType)
         ->where('year',Carbon::now()->format('Y'))
         ->get();

         foreach($employeeLeaves as $employeeLeave) {

            //find the employee leave with the employee leave id
            $employeeLeaveId=$employeeLeave->id;
            $employeeLeave=EmployeeLeave::find($employeeLeaveId);

            //update the record
            $employeeLeave->status='Invalid';
            $employeeLeave->save();
         }
      }

      //update administrator's leave record -----------------------------------------
      //find administrators with the leave grade
      $admins=DB::table('admins')
      ->where('leave_grade',$leaveGradeId)
      ->get();

      //for each administrator who is assigned with the leave grade
      foreach($admins as $admin) {
         //find the administrator with the admin id
         $adminId=$admin->id;
         $admin=Admin::find($adminId);

         //find their related leave record (same leave type, present year)
         $adminLeaves=DB::table('admin_leaves')
         ->where('admin',$admin->id)
         ->where('leave_type',$leaveEntitlements->leaveType)
         ->where('year',Carbon::now()->format('Y'))
         ->get();

         foreach($adminLeaves as $adminLeave) {

            //find the employee leave with the employee leave id
            $adminLeaveId=$adminLeave->id;
            $adminLeave=AdminLeave::find($adminLeaveId);

            //update the record
            $adminLeave->status='Invalid';
            $adminLeave->save();
         }
      }

      $leaveEntitlements->delete();

      Session::flash('success',"Leave entitlement deleted successfully!");
      return redirect()->route('leaveEntitlement',['id'=>$leaveGradeId]);
   }
}
