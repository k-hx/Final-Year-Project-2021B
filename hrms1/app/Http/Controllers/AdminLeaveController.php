<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use DB;
Use Auth;
use Carbon\Carbon;
use App\Models\AdminLeave;
use App\Models\Admin;

class AdminLeaveController extends Controller
{
   public function __construct() {
      $this->middleware('auth');
   }

   public function createLeaveRecord() {
      $isSuccess=0;

      $adminLeaves=DB::table('admin_leaves')
                     ->where('year','=',Carbon::now()->format('Y'))
                     ->get();

      if($adminLeaves->isNotEmpty()) {
         $isSuccess=2;
      } else {
         $isSuccess=1;
         $admins=DB::table('admins')
                     ->where('status','=','ACTIVE')
                     ->get();

         $previousAdminLeaves=DB::table('admin_leaves')
                                 ->where('year','!=',Carbon::now()->format('Y'))
                                 ->get();

         foreach($previousAdminLeaves as $previousAdminLeave) {
            $previousAdminLeave=AdminLeave::find($previousAdminLeave->id);
            $previousAdminLeave->status='Invalid';
            $previousAdminLeave->save();
         }

         foreach($admins as $admin) {
            $leaveEntitlements=DB::table('leave_entitlements')
                              ->where('leaveGrade','=',$admin->leave_grade)
                              ->get();

            foreach($leaveEntitlements as $leaveEntitlement) {
               //create admin leave record
               $createAdminLeave=AdminLeave::create([
                  'admin'=>$admin->id,
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
          Session::flash('success',"Admin leave record for current year is created successfully!");
       } else if($isSuccess = 2) {
          Session::flash('primary',"Admin leave record for current year is already existed!");
       }

       return redirect()->route('allAdminsLeaveGrade');
  }

  public function showAnAdminsLeave($id) {
     $admins=DB::table('admins')
     ->leftjoin('leave_grades','leave_grades.id','=','admins.leave_grade')
     ->select('leave_grades.name as leaveGradeName', 'admins.*')
     ->where('admins.id','=',$id)
     ->get();

     foreach($admins as $admin) {
        $admin=Admin::find($admin->id);
        $supervisor=$admin->supervisor;
     }

     if($supervisor == Auth::id()) {
        $adminLeaves=DB::table('admin_leaves')
       ->leftjoin('leave_types','leave_types.id','=','admin_leaves.leave_type')
       ->select('leave_types.name as leaveTypeName','admin_leaves.*')
       ->orderBy('leave_types.id','asc')
       ->where('admin_leaves.admin',$id)
       ->get();

       return view('admin/leave/adminsLeaveGrade')->with('admins',$admins)
       ->with('adminLeaves',$adminLeaves);
    } else {
      Session::flash('danger',"The admin is not under your supervision.");
      return redirect()->route('allAdminsLeaveGrade');
   }
  }


  public function showAdminOwnLeave() {
     $admins=DB::table('admins')
     ->leftjoin('leave_grades','leave_grades.id','=','admins.leave_grade')
     ->select('leave_grades.name as leaveGradeName', 'admins.*')
     ->where('admins.id',Auth::id())
     ->get();

     $adminLeaves=DB::table('admin_leaves')
     ->leftjoin('leave_types','leave_types.id','=','admin_leaves.leave_type')
     ->select('leave_types.name as leaveTypeName','admin_leaves.*')
     ->where('admin_leaves.admin',Auth::id())
     ->orderBy('leave_type','asc')
     ->get();

     return view('admin/leave/ownLeaveGrade')
     ->with('admins',$admins)
     ->with('adminLeaves',$adminLeaves);
  }
}
