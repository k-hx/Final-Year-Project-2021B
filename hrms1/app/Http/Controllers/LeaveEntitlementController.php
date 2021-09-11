<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\LeaveType;
use App\Models\LeaveGrade;
use App\Models\LeaveEntitlement;
use Session;

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

      return view('leaveEntitlement')->with('leaveGrades',$leaveGrades)
                                          ->with('currentEntitlements',$currentEntitlements)
                                          ->with('leaveTypes',DB::table('leave_types')->orderBy('name','asc')->get());
   }

   public function addLeaveEntitlement($id) {
      $r=request();
      $addLeaveEntitlements=LeaveEntitlement::create([
         'leaveGrade'=>$r->id,
         'leaveType'=>$r->leaveType,
         'num_of_days'=>$r->num_of_days,
      ]);

      $isExist=0;
      $employees=Employee::all()->where('leave_grade',$r->id);
      foreach($employees as $employee) {
         $isExist=0;

         $employeeLeaves=EmployeeLeave::all()
         ->where('employee',$employee->id)
         ->where('leave_type',$r->leaveType)
         ->where('year',Carbon::now()->format('Y'));

         if($employeeLeaves->isNotEmpty()) {
            $isExits=1;
            $employeeLeaves->total_days=$r->num_of_days;
            $remaining_days=($r->num_of_days)-($employeeLeave->leaves_taken);
            if($remaining_days<0) {
               $remaining_days=0;
            }
            $employeeLeave->remaining_days=$remaining_days;
            $employeeLeave->status='Valid';
            $employeeLeave->save();
         } else {
            $createEmployeeLeave=EmployeeLeave::create([
               'employee'=>$employee,
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

      return view('editLeaveEntitlement')->with('leaveGrades',$leaveGrades)
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

      //update employee's leave record


      Session::flash('success',"Leave entitlement updated successfully!");
      return redirect()->route('leaveEntitlement',['id'=>$leaveGradeId]);
   }

   public function deleteLeaveEntitlement($leaveGradeId,$id) {
      $leaveEntitlements=LeaveEntitlement::find($id);
      $leaveEntitlements->delete();

      //update employee's leave record

      Session::flash('success',"Leave entitlement deleted successfully!");
      return redirect()->route('leaveEntitlement',['id'=>$leaveGradeId]);
   }
}
