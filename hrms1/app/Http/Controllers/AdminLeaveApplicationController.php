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

      $leaveApplications=DB::table('leave_applications')
      ->where('admin',Auth::id())
      ->where('status','=','Applied')
      ->get();

      return view('admin/applyLeave')->with('admins',$admins)
      ->with('adminLeaves',$adminLeaves)
      ->with('leaveApplications',$leaveApplications);
   }

   public function submitApplication() {
      $r=request();
      if($r->file('document')!='') {
         $document=$r->file('document');
         $document->move('documents',$document->getClientOriginalName());
         $documentName=$document->getClientOriginalName();
      } else {
         $documentName='';
      }

      $applyLeave=LeaveApplication::create([
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

   public function showLeaveApplicationList() {
      $admins=Admin::all()->where('id',Auth::id());
      $leaveApplications=DB::table('leave_applications')
      ->get();

      foreach($leaveApplications as $leaveApplication) {
         $leaveApplication=LeaveApplication::find($leaveApplication->id);
         $today=Carbon::now();
         $leaveApplicationDate=$leaveApplication->start_date;
         if($today->gt($leaveApplicationDate) && ($leaveApplication->status === 'Applied')) {
            $leaveApplication->status='Expired';
            $leaveApplication->save();
         }
      }

      $leaveApplications=DB::table('leave_applications')
      ->leftjoin('leave_types','leave_types.id','=','leave_applications.leave_type_id')
      ->leftjoin('admins','admins.id','=','leave_applications.leave_approver')
      ->select('leave_types.name as leaveTypeName','admins.id as leaveApproverId','admins.full_name as leaveApproverName','leave_applications.*')
      ->where('leave_applications.admin','=',Auth::id())
      ->orderBy('id','asc')
      ->get();

      return view('admin/leaveApplicationList')->with('admins',$admins)
      ->with('leaveApplications',$leaveApplications);
   }

}
