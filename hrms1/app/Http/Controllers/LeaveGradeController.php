<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\LeaveType;
use Session;

class LeaveGradeController extends Controller
{
   public function __construct() {
        $this->middleware('auth');
   }

   public function showCreatePage() {
      return view('createLeaveGrade')->with('leaveTypes',LeaveType::all());
   }

    public function store() {
      $r=request();
      $addLeaveGrade=LeaveGrade::create([
         'name'=>$r->name,
      ]);

      Session::flash('success',"Leave grade created successfully!");
      return redirect()->route('showLeaveGrades');
   }

   public function addLeaveEntitlements() {
      $r=request();
      $addLeaveEntitlements=LeaveEntitlements::create([
         'leaveGrade'=>$r->leaveGrade,
         'leaveType'=>$r->leaveType,
         'num_of_days'=>$r->num_of_days,
      ]);

      Session::flash('success',"Leave entitlement added successfully!");
      return redirect()->route('showLeaveGrades');
   }


}
