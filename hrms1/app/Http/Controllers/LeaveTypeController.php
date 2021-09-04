<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\LeaveType;
use Session;

class LeaveTypeController extends Controller
{
   public function __construct() {
        $this->middleware('auth');
   }

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

   public function edit($id) {
      $leaveTypes=LeaveType::all()->where('id',$id);
      return view('editLeaveType')->with('leaveTypes',$leaveTypes);
   }

   public function update() {
      $r=request();
      $leaveTypes=LeaveType::find($r->id);

      $leaveTypes->name=$r->name;
      $leaveTypes->min_num_of_days=$r->min_num_of_days;
      $leaveTypes->save();

      Session::flash('success',"Leave type updated successfully!");
      return redirect()->route('showLeaveTypes');
   }

   public function delete($id) {
      $leaveTypes=LeaveType::find($id);
      $leaveTypes->delete();
      return redirect()->route('showLeaveTypes');
   }
}
