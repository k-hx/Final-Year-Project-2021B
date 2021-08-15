<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\LeaveType;
use Session;

class LeaveTypeController extends Controller
{
   public function store() {
      $r=request();
      $addLeaveType=LeaveType::create([
         'name'=>$r->name,
         'min_num_of_days'=>$r->min_num_of_days,
      ]);

      Session::flash('success',"Leave type created successfully!");
      return redirect()->route('showLeaveTypes');
   }

   public function show() {
      $leaveTypes=LeaveType::all();
      return view('showLeaveTypes')->with('leaveTypes',$leaveTypes);
   }
}
