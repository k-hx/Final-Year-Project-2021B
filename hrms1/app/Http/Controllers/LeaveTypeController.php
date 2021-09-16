<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\LeaveType;
use App\Models\LeaveEntitlement;
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
         'status'=>'Added',
      ]);

      Session::flash('success',"Leave type created successfully!");
      return redirect()->route('showLeaveTypes');
   }

   public function show() {
      $leaveTypes=DB::table('leave_types')
                     ->where('status','Added')
                     ->orWhere('status','Edited')
                     ->orderBy('id','asc')
                     ->get();

      return view('admin/leave/showLeaveTypes')->with('leaveTypes',$leaveTypes);
   }

   public function edit($id) {
      $leaveTypes=LeaveType::all()->where('id',$id);
      return view('admin/leave/editLeaveType')->with('leaveTypes',$leaveTypes);
   }

   public function update() {
      $r=request();
      $leaveTypes=LeaveType::find($r->id);

      $leaveTypes->name=$r->name;
      $leaveTypes->min_num_of_days=$r->min_num_of_days;
      $leaveTypes->status='Edited';
      $leaveTypes->save();

      Session::flash('success',"Leave type updated successfully!");
      return redirect()->route('showLeaveTypes');
   }

   public function delete($id) {
      $leaveTypes=LeaveType::find($id);
      $leaveTypes->status='Deleted';
      $leaveTypes->save();

      $leaveEntitlements=LeaveEntitlement::all()->where('leaveType',$id);
      foreach($leaveEntitlements as $leaveEntitlement) {
         $leaveEntitlement->delete();
      }

      Session::flash('success',"Leave type deleted successfully!");
      return redirect()->route('showLeaveTypes');
   }
}
