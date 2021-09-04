<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\LeaveType;
use Session;

class LeaveEntitlementController extends Controller
{
   public function __construct() {
        $this->middleware('auth');
   }

   public function show($id) {
      $currentEntitlements=LeaveEntitlement::all()->where('leaveGrade',$id);

      return view('editLeaveGrade')->with('leaveTypes',LeaveType::all());
   }

   public function addLeaveEntitlement($id) {
      $r=request();
      $addLeaveEntitlements=LeaveEntitlement::create([
         'leaveGrade'=>$r->$id,
         'leaveType'=>$r->leaveType,
         'num_of_days'=>$r->num_of_days,
      ]);

      Session::flash('success',"Leave entitlement added successfully!");
      return redirect()->route('showLeaveGrades');
   }
}
