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
      // $currentEntitlements=LeaveEntitlement::all()->where('leaveGrade',$id);

      $currentEntitlements=DB::table('leave_entitlements')
                           ->leftjoin('leave_types','leave_types.id','=','leave_entitlements.leaveType')
                           ->select('leave_entitlements.leaveType as leaveTypeId','leave_types.name as leaveTypeName','leave_entitlements.*')
                           ->where('leave_entitlements.leaveGrade','=',$id)
                           ->get();

      return view('leaveEntitlement')->with('leaveGrades',$leaveGrades)
                                          ->with('currentEntitlements',$currentEntitlements)
                                          ->with('leaveTypes',LeaveType::all());
   }

   public function addLeaveEntitlement() {
      $r=request();
      $addLeaveEntitlements=LeaveEntitlement::create([
         'leaveGrade'=>$r->id,
         'leaveType'=>$r->leaveType,
         'num_of_days'=>$r->num_of_days,
      ]);

      Session::flash('success',"Leave entitlement added successfully!");
      return redirect()->route('showLeaveGrades');
   }
}
