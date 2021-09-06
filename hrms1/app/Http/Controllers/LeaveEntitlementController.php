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
                           ->get();

      return view('leaveEntitlement')->with('leaveGrades',$leaveGrades)
                                          ->with('currentEntitlements',$currentEntitlements)
                                          ->with('leaveTypes',LeaveType::all());
   }

   public function addLeaveEntitlement($id) {
      $r=request();
      $addLeaveEntitlements=LeaveEntitlement::create([
         'leaveGrade'=>$r->id,
         'leaveType'=>$r->leaveType,
         'num_of_days'=>$r->num_of_days,
      ]);

      Session::flash('success',"Leave entitlement added successfully!");
      return redirect()->route('leaveEntitlement', ['id' => $id]);
   }

   public function edit($leaveGradeId,$id) {
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

      return view('editLeaveEntitlement')->with('leaveEntitlements',$leaveEntitlements)
                                          ->with('currentEntitlements',$currentEntitlements)
                                         ->with('leaveTypes',LeaveType::all());
   }

   public function updateLeaveEntitlement($id) {
      $r=request();
      $leaveEntitlements=LeaveEntitlement::find($id);

      $leaveEntitlements->leaveType=$r->leaveType;
      $leaveEntitlements->num_of_days=$r->num_of_days;
      $leaveEntitlements->save();

      Session::flash('success',"Leave entitlement updated successfully!");
      return redirect()->route('leaveEntitlement');
   }

   public function deleteLeaveEntitlement($id) {
      $leaveEntitlements=LeaveEntitlement::find($id);
      $leaveEntitlements->delete();

      return redirect()->route('leaveEntitlement');
   }
}
