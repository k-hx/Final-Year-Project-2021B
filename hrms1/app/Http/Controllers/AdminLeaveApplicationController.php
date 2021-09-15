<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\AdminLeaveApplication;
use App\Models\Admin;
use App\Models\AdminLeave;
use App\Models\LeaveType;
use Session;
Use Auth;
use Carbon\Carbon;

class AdminLeaveApplicationController extends Controller
{
   public function __construct() {
      $this->middleware('auth');
   }


   //++++++++++++++++++++++++++ SHOW APPLY LEAVE PAGE ++++++++++++++++++++++++++

   public function showApplyLeavePage() {
      $admins=Admin::all()->where('id',Auth::id());

      $adminLeaves=DB::table('admin_leaves')
      ->leftjoin('leave_types','leave_types.id','admin_leaves.leave_type')
      ->where('admin',Auth::id())
      ->where('year',Carbon::now()->format('Y'))
      ->where('admin_leaves.status','=','Valid')
      ->select('leave_types.name as leaveTypeName','admin_leaves.*')
      ->orderBy('leave_types.name','asc')
      ->get();

      $adminLeaveApplications=DB::table('admin_leave_applications')
      ->where('admin',Auth::id())
      ->where('status','=','Applied')
      ->get();

      return view('admin/leave/leave/applyLeave')->with('admins',$admins)
      ->with('adminLeaves',$adminLeaves)
      ->with('adminLeaveApplications',$adminLeaveApplications);
   }


   //++++++++++++++++++++++++++ SUBMIT LEAVE APPLICATION ++++++++++++++++++++++++++

   public function submitApplication() {
      $r=request();
      if($r->file('document')!='') {
         $document=$r->file('document');
         $document->move('documents',$document->getClientOriginalName());
         $documentName=$document->getClientOriginalName();
      } else {
         $documentName='';
      }

      $applyLeave=AdminLeaveApplication::create([
         'database'=>$r->form,
         'admin'=>$r->admin,
         'leave_type_id'=>$r->leaveTypeId,
         'leave_type_name'=>$r->leaveTypeName,
         'start_date'=>$r->startDate,
         'end_date'=>$r->endDate,
         'num_of_days'=>$r->numOfDays,
         'reason'=>$r->reason,
         'document'=>$documentName,
         'status'=>'Applied',
         'leave_approver'=>$r->leaveApprover,
      ]);

      Session::flash('success',"Leave application submitted successfully!");
      return redirect()->route('showAdminOwnLeaveApplicationList');
   }


   //++++++++++++++++++++++++++ SHOW ADMINISTRATOR OWN LEAVE APPLICATION LIST ++++++++++++++++++++++++++

   public function showOwnLeaveApplicationList() {
      $admins=Admin::all()->where('id',Auth::id());
      $adminLeaveApplications=DB::table('admin_leave_applications')
      ->get();

      foreach($adminLeaveApplications as $adminLeaveApplication) {
         $adminLeaveApplication=AdminLeaveApplication::find($adminLeaveApplication->id);
         $today=Carbon::now();
         $adminLeaveApplicationDate=$adminLeaveApplication->start_date;
         if($today->gt($adminLeaveApplicationDate) && ($adminLeaveApplication->status === 'Applied')) {
            $adminLeaveApplication->status='Expired';
            $adminLeaveApplication->save();
         }
      }

      $adminLeaveApplications=DB::table('admin_leave_applications')
      ->leftjoin('leave_types','leave_types.id','=','admin_leave_applications.leave_type_id')
      ->leftjoin('admins','admins.id','=','admin_leave_applications.leave_approver')
      ->select('leave_types.name as leaveTypeName','admins.id as leaveApproverId','admins.full_name as leaveApproverName','admin_leave_applications.*')
      ->where('admin_leave_applications.admin','=',Auth::id())
      ->orderBy('id','asc')
      ->get();

      return view('admin/leave/leave/ownLeaveApplicationList')
      ->with('admins',$admins)
      ->with('adminLeaveApplications',$adminLeaveApplications);
   }


   //++++++++++++++++++++++++++ SHOW ADMINISTRATOR LEAVE APPLICATION LIST ++++++++++++++++++++++++++

   public function showAdminLeaveApplicationList() {
      $admins=Admin::all()->where('id',Auth::id());
      $adminLeaveApplications=DB::table('admin_leave_applications')
      ->get();

      foreach($adminLeaveApplications as $adminLeaveApplication) {
         $adminLeaveApplication=AdminLeaveApplication::find($adminLeaveApplication->id);
         $today=Carbon::now();
         $adminLeaveApplicationDate=$adminLeaveApplication->start_date;
         if($today->gt($adminLeaveApplicationDate)) {
            $adminLeaveApplication->status='Expired';
            $adminLeaveApplication->save();
         }
      }

      $adminLeaveApplications=DB::table('admin_leave_applications')
      ->leftjoin('leave_types','leave_types.id','=','admin_leave_applications.leave_type_id')
      ->leftjoin('admins','admins.id','=','admin_leave_applications.admin')
      ->select('leave_types.name as leaveTypeName','admins.id as adminId','admins.full_name as adminName','admin_leave_applications.*')
      ->where('admin_leave_applications.leave_approver','=',Auth::id())
      ->get();

      return view('admin/leave/leave/adminLeaveApplicationList')
      ->with('admins',$admins)
      ->with('adminLeaveApplications',$adminLeaveApplications);
   }


   //+++++++++++++++++++ APPROVE ADMINISTRATOR LEAVE APPLICATION +++++++++++++++++++

   public function approve($adminId,$id) {
      $adminLeaveApplications=AdminLeaveApplication::find($id);
      $adminLeaveApplications->status='Approved';
      $adminLeaveApplications->save();

      //update leave taken for the administrator ------------------------------------
      $adminLeaves=DB::table('admin_leaves')
      ->where('admin','=',$adminId)
      ->where('leave_type','=',$adminLeaveApplications->leave_type_id)
      ->where('year','=',Carbon::now()->format('Y'))
      ->get();

      foreach($adminLeaves as $adminLeave) {
         $adminLeaveId=$adminLeave->id;
         $adminLeave=AdminLeave::find($adminLeaveId);

         $currentLeavesTaken=$adminLeave->leaves_taken;
         $adminLeave->leaves_taken=$currentLeavesTaken+($adminLeaveApplications->num_of_days);
         $adminLeave->remaining_days=($adminLeave->remaining_days)-($adminLeaveApplications->num_of_days);
         $adminLeave->save();
      }

      Session::flash('success',"Leave application approved successfully!");
      return redirect()->route('showAdminLeaveApplicationList');
   }


   //+++++++++++++++++++ APPROVE MULTIPLE LEAVE APPLICATION +++++++++++++++++++

   public function approveMultiple() {
      $r=request();

      $adminLeaveApplications=$r->input('adminLeaveApplication');
      foreach($adminLeaveApplications as $adminLeaveApplication => $value) {
         $adminLeaveApplication=AdminLeaveApplication::find($value);

         if($adminLeaveApplication->status != "Approved") {
            $adminLeaveApplication->status='Approved';
            $adminLeaveApplication->save();

            $adminId=$adminLeaveApplication->admin;
            $adminLeaves=DB::table('admin_leaves')
            ->where('admin','=',$adminId)
            ->where('leave_type','=',$adminLeaveApplication->leave_type_id)
            ->where('year','=',Carbon::now()->format('Y'))
            ->get();

            foreach($adminLeaves as $adminLeave) {
               $adminLeaveId=$adminLeave->id;
               $adminLeave=AdminLeave::find($adminLeaveId);

               $currentLeavesTaken=$adminLeave->leaves_taken;
               $adminLeave->leaves_taken=$currentLeavesTaken+($adminLeaveApplication->num_of_days);
               $adminLeave->remaining_days=($adminLeave->remaining_days)-($adminLeaveApplication->num_of_days);
               $adminLeave->save();
            }
         }
      }

      Session::flash('success',"Leave applications approved successfully!");
      return redirect()->route('showAdminLeaveApplicationList');
   }


   //+++++++++++++++ REJECT ADMINISTRATOR'S LEAVE APPLICATION +++++++++++++++++

   public function reject($adminId,$id) {
      $adminLeaveApplications=AdminLeaveApplication::find($id);

      $previousStatus=$adminLeaveApplications->status;
      if($previousStatus = "Approved") {
         //update leave taken
         $adminLeaves=DB::table('admin_leaves')
         ->where('admin','=',$adminId)
         ->where('leave_type','=',$adminLeaveApplications->leave_type_id)
         ->where('year','=',Carbon::now()->format('Y'))
         ->get();

         foreach($adminLeaves as $adminLeave) {
            $adminLeaveId=$adminLeave->id;
            $adminLeave=AdminLeave::find($adminLeaveId);

            $currentLeavesTaken=$adminLeave->leaves_taken;
            $adminLeave->leaves_taken=$currentLeavesTaken-($adminLeaveApplications->num_of_days);
            $adminLeave->remaining_days=($adminLeave->remaining_days)+($adminLeaveApplications->num_of_days);
            $adminLeave->save();
         }
      }

      $adminLeaveApplications->status='Rejected';
      $adminLeaveApplications->save();

      Session::flash('success',"Leave application rejected successfully!");
      return redirect()->route('showAdminLeaveApplicationList');
   }


   //+++++++++++++++ REJECT MULTIPLE ADMINISTRATOR'S LEAVE APPLICATION +++++++++++++++++

   public function rejectMultiple() {
      $r=request();

      $adminLeaveApplications=$r->input('adminLeaveApplication');
      foreach($adminLeaveApplications as $adminLeaveApplication => $value) {
         $adminLeaveApplication=AdminLeaveApplication::find($value);
         $previousStatus=$adminLeaveApplication->status;
         $adminLeaveApplication->status='Rejected';
         $adminLeaveApplication->save();

         if($previousStatus === "Approved") {
            $adminId=$adminLeaveApplication->admin;
            $adminLeaves=DB::table('admin_leaves')
            ->where('admin','=',$adminId)
            ->where('leave_type','=',$adminLeaveApplication->leave_type_id)
            ->where('year','=',Carbon::now()->format('Y'))
            ->get();

            foreach($adminLeaves as $adminLeave) {
               $adminLeavesId=$adminLeave->id;
               $adminLeave=AdminLeave::find($adminLeavesId);

               $currentLeavesTaken=$adminLeave->leaves_taken;
               $adminLeave->leaves_taken=$currentLeavesTaken-($adminLeaveApplication->num_of_days);
               $adminLeave->remaining_days=($adminLeave->remaining_days)+($adminLeaveApplication->num_of_days);
               $adminLeave->save();
            }
         }
      }

      Session::flash('success',"Leave application rejected successfully!");
      return redirect()->route('showAdminLeaveApplicationList');
   }

   //+++++++++++++++ ADMINISTRATOR CANCEL OWN LEAVE APPLICATION +++++++++++++++++

   public function cancel($adminId,$id) {
      $adminLeaveApplications=AdminLeaveApplication::find($id);

      $previousStatus=$adminLeaveApplications->status;
      if($previousStatus = "Approved") {
         //update leave taken
         $adminLeaves=DB::table('admin_leaves')
         ->where('admin','=',$adminId)
         ->where('leave_type','=',$adminLeaveApplications->leave_type_id)
         ->where('year','=',Carbon::now()->format('Y'))
         ->get();

         foreach($adminLeaves as $adminLeave) {
            $adminLeaveId=$adminLeave->id;
            $adminLeave=AdminLeave::find($adminLeaveId);

            $currentLeavesTaken=$adminLeave->leaves_taken;
            $adminLeave->leaves_taken=$currentLeavesTaken-($adminLeaveApplications->num_of_days);
            $adminLeave->remaining_days=($adminLeave->remaining_days)+($adminLeaveApplications->num_of_days);
            $adminLeave->save();
         }
      }

      $adminLeaveApplications->status='Cancelled';
      $adminLeaveApplications->save();

      return redirect()->route('showAdminOwnLeaveApplicationList');
   }

}
