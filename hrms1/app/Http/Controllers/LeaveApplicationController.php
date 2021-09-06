<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\LeaveApplication;
use App\Models\Admin;
use App\Models\Employee;
use App\Models\LeaveType;
use Session;
Use Auth;

class LeaveApplicationController extends Controller
{
   public function __construct() {
        $this->middleware('auth');
   }

   public function showApplyLeavePage() {
      $employees=Employee::all()->where('id',Auth::id());
      return view('applyLeave')->with('employees',$employees)
                              ->with('leaveTypes',LeaveType::all());
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
         'employee'=>$r->employee,
         'leave_type'=>$r->leaveType,
         'start_date_time'=>$r->startDateTime,
         'end_date_time'=>$r->endDateTime,
         'reason'=>$r->reason,
         'document'=>$documentName,
         'status'=>'Applied',
         'leave_approver'=>$r->leaveApprover,
      ]);

      Session::flash('success',"Leave application submitted successfully!");
      return redirect()->route('showLeaveApplicationList');
   }

   public function showLeaveApplicationList() {
      $employees=Employee::all()->where('id',Auth::id());
      $leaveApplications=DB::table('leave_applications')
                        ->leftjoin('leave_types','leave_types.id','=','leave_applications.leave_type')
                        ->leftjoin('admins','admins.id','=','leave_applications.leave_approver')
                        ->select('leave_types.name as leaveTypeName','admins.id as leaveApproverId','admins.name as leaveApproverName','leave_applications.*')
                        ->where('leave_applications.employee','=',Auth::id())
                        ->get();
      return view('leaveApplicationList')->with('employees',$employees)
                              ->with('leaveApplications',$leaveApplications);
   }

   public function showLeaveApplicationListAdmin() {
      $admins=Admin::all()->where('id',Auth::id());
      $leaveApplications=DB::table('leave_applications')
                        ->leftjoin('leave_types','leave_types.id','=','leave_applications.leave_type')
                        ->leftjoin('employees','employees.id','=','leave_applications.employee')
                        ->select('leave_types.name as leaveTypeName','employees.id as employeeId','employees.full_name as employeeName','leave_applications.*')
                        ->where('leave_applications.leave_approver','=',Auth::id())
                        ->get();
      return view('leaveApplicationListAdmin')->with('admins',$admins)
                              ->with('leaveApplications',$leaveApplications);
   }

   public function approve($id) {
      $leaveApplications=LeaveApplication::find($id);
      $leaveApplications->status='Approved';
      $leaveApplications->save();

      return redirect()->route('showLeaveApplicationListAdmin');
   }

   public function reject($id) {
      $leaveApplications=LeaveApplication::find($id);
      $leaveApplications->status='Rejected';
      $leaveApplications->save();

      return redirect()->route('showLeaveApplicationListAdmin');
   }


   public function cancel($id) {
      $leaveApplications=LeaveApplication::find($id);
      $leaveApplications->status='Cancelled';
      $leaveApplications->save();

      return redirect()->route('showLeaveApplicationList');
   }
}
