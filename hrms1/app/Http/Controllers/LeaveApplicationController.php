<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\LeaveApplication;
use App\Models\Admin;
use App\Models\Employee;
use App\Models\EmployeeLeave;
use App\Models\LeaveType;
use Session;
Use Auth;
use Carbon\Carbon;

class LeaveApplicationController extends Controller
{
   public function __construct() {
      $this->middleware('auth');
   }

   public function showApplyLeavePage() {
      $employees=Employee::all()->where('id',Auth::id());

      $employeeLeaves=DB::table('employee_leaves')
      ->leftjoin('leave_types','leave_types.id','employee_leaves.leave_type')
      ->where('employee',Auth::id())
      ->where('year',Carbon::now()->format('Y'))
      ->where('employee_leaves.status','=','Valid')
      ->select('leave_types.name as leaveTypeName','employee_leaves.*')
      ->orderBy('leave_types.name','asc')
      ->get();

      $leaveApplications=DB::table('leave_applications')
      ->where('employee',Auth::id())
      ->where('status','=','Applied')
      ->get();

      return view('employee/leave/applyLeave')->with('employees',$employees)
      ->with('employeeLeaves',$employeeLeaves)
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
         'employee'=>$r->employee,
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
      return redirect()->route('showLeaveApplicationList');
   }

   public function showLeaveApplicationList() {
      $employees=Employee::all()->where('id',Auth::id());
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
      ->where('leave_applications.employee','=',Auth::id())
      ->orderBy('id','asc')
      ->get();

      return view('employee/leave/leaveApplicationList')->with('employees',$employees)
      ->with('leaveApplications',$leaveApplications);
   }

   public function showLeaveApplicationListAdmin() {
      $admins=Admin::all()->where('id',Auth::id());
      $leaveApplications=DB::table('leave_applications')
      ->get();

      foreach($leaveApplications as $leaveApplication) {
         $leaveApplication=LeaveApplication::find($leaveApplication->id);
         $today=Carbon::now();
         $leaveApplicationDate=$leaveApplication->start_date;
         if($today->gt($leaveApplicationDate)) {
            $leaveApplication->status='Expired';
            $leaveApplication->save();
         }
      }

      $leaveApplications=DB::table('leave_applications')
      ->leftjoin('leave_types','leave_types.id','=','leave_applications.leave_type_id')
      ->leftjoin('employees','employees.id','=','leave_applications.employee')
      ->select('leave_types.name as leaveTypeName','employees.id as employeeId','employees.full_name as employeeName','leave_applications.*')
      ->where('leave_applications.leave_approver','=',Auth::id())
      ->get();

      return view('admin/leave/leaveApplicationList')->with('admins',$admins)
      ->with('leaveApplications',$leaveApplications);
   }

   public function approve($employeeId,$id) {
      $leaveApplications=LeaveApplication::find($id);
      $leaveApplications->status='Approved';
      $leaveApplications->save();

      //update leave taken for the employee ------------------------------------
      $employeeLeaves=DB::table('employee_leaves')
      ->where('employee','=',$employeeId)
      ->where('leave_type','=',$leaveApplications->leave_type_id)
      ->where('year','=',Carbon::now()->format('Y'))
      ->get();

      foreach($employeeLeaves as $employeeLeave) {
         $employeeLeaveId=$employeeLeave->id;
         $employeeLeave=EmployeeLeave::find($employeeLeaveId);

         $currentLeavesTaken=$employeeLeave->leaves_taken;
         $employeeLeave->leaves_taken=$currentLeavesTaken+($leaveApplications->num_of_days);
         $employeeLeave->remaining_days=($employeeLeave->remaining_days)-($leaveApplications->num_of_days);
         $employeeLeave->save();
      }

      Session::flash('success',"Leave application approved successfully!");
      return redirect()->route('showLeaveApplicationListAdmin');
   }

   public function approveMultiple() {
      $r=request();

      $leaveApplications=$r->input('leaveApplication');
      foreach($leaveApplications as $leaveApplication => $value) {
         $application=LeaveApplication::find($value);
         $application->status='Approved';
         $application->save();

         $employeeId=$application->employee;
         $employeeLeaves=DB::table('employee_leaves')
         ->where('employee','=',$employeeId)
         ->where('leave_type','=',$application->leave_type_id)
         ->where('year','=',Carbon::now()->format('Y'))
         ->get();

         foreach($employeeLeaves as $employeeLeave) {
            $employeeLeaveId=$employeeLeave->id;
            $employeeLeave=EmployeeLeave::find($employeeLeaveId);

            $currentLeavesTaken=$employeeLeave->leaves_taken;
            $employeeLeave->leaves_taken=$currentLeavesTaken+($application->num_of_days);
            $employeeLeave->remaining_days=($employeeLeave->remaining_days)-($application->num_of_days);
            $employeeLeave->save();
         }
      }

      Session::flash('success',"Leave applications approved successfully!");
      return redirect()->route('showLeaveApplicationListAdmin');
   }

   public function reject($employeeId,$id) {
      $leaveApplications=LeaveApplication::find($id);

      $previousStatus=$leaveApplications->status;
      if($previousStatus = "Approved") {
         //update leave taken
         $employeeLeaves=DB::table('employee_leaves')
         ->where('employee','=',$employeeId)
         ->where('leave_type','=',$leaveApplications->leave_type_id)
         ->where('year','=',Carbon::now()->format('Y'))
         ->get();

         foreach($employeeLeaves as $employeeLeave) {
            $employeeLeaveId=$employeeLeave->id;
            $employeeLeave=EmployeeLeave::find($employeeLeaveId);

            $currentLeavesTaken=$employeeLeave->leaves_taken;
            $employeeLeave->leaves_taken=$currentLeavesTaken-($leaveApplications->num_of_days);
            $employeeLeave->remaining_days=($employeeLeave->remaining_days)+($leaveApplications->num_of_days);
            $employeeLeave->save();
         }
      }

      $leaveApplications->status='Rejected';
      $leaveApplications->save();

      Session::flash('success',"Leave application rejected successfully!");
      return redirect()->route('showLeaveApplicationListAdmin');
   }

   public function rejectMultiple() {
      $r=request();

      $leaveApplications=$r->input('leaveApplication');
      foreach($leaveApplications as $leaveApplication => $value) {
         $application=LeaveApplication::find($value);
         $previousStatus=$application->status;
         $application->status='Rejected';
         $application->save();

         if($previousStatus = "Approved") {
            $employeeId=$application->employee;
            $employeeLeaves=DB::table('employee_leaves')
            ->where('employee','=',$employeeId)
            ->where('leave_type','=',$application->leave_type_id)
            ->where('year','=',Carbon::now()->format('Y'))
            ->get();

            foreach($employeeLeaves as $employeeLeave) {
               $employeeLeaveId=$employeeLeave->id;
               $employeeLeave=EmployeeLeave::find($employeeLeaveId);

               $currentLeavesTaken=$employeeLeave->leaves_taken;
               $employeeLeave->leaves_taken=$currentLeavesTaken-($application->num_of_days);
               $employeeLeave->remaining_days=($employeeLeave->remaining_days)+($application->num_of_days);
               $employeeLeave->save();
            }
         }
      }

      Session::flash('success',"Leave application rejected successfully!");
      return redirect()->route('showLeaveApplicationListAdmin');
   }

   public function cancel($employeeId,$id) {
      $leaveApplications=LeaveApplication::find($id);

      $previousStatus=$leaveApplications->status;
      if($previousStatus = "Approved") {
         //update leave taken
         $employeeLeaves=DB::table('employee_leaves')
         ->where('employee','=',$employeeId)
         ->where('leave_type','=',$leaveApplications->leave_type_id)
         ->where('year','=',Carbon::now()->format('Y'))
         ->get();

         foreach($employeeLeaves as $employeeLeave) {
            $employeeLeaveId=$employeeLeave->id;
            $employeeLeave=EmployeeLeave::find($employeeLeaveId);

            $currentLeavesTaken=$employeeLeave->leaves_taken;
            $employeeLeave->leaves_taken=$currentLeavesTaken-($leaveApplications->num_of_days);
            $employeeLeave->remaining_days=($employeeLeave->remaining_days)+($leaveApplications->num_of_days);
            $employeeLeave->save();
         }
      }

      $leaveApplications->status='Cancelled';
      $leaveApplications->save();

      return redirect()->route('showLeaveApplicationList');
   }

   public function cancelMultiple() {
      $r=request();

      $leaveApplications=$r->input('leaveApplication');
      foreach($leaveApplications as $leaveApplication => $value) {
         $application=LeaveApplication::find($value);
         $previousStatus=$application->status;
         $application->status='Cancelled';
         $application->save();

         if($previousStatus = "Approved") {
            $employeeId=$application->employee;
            $employeeLeaves=DB::table('employee_leaves')
            ->where('employee','=',$employeeId)
            ->where('leave_type','=',$application->leave_type_id)
            ->where('year','=',Carbon::now()->format('Y'))
            ->get();

            foreach($employeeLeaves as $employeeLeave) {
               $employeeLeaveId=$employeeLeave->id;
               $employeeLeave=EmployeeLeave::find($employeeLeaveId);

               $currentLeavesTaken=$employeeLeave->leaves_taken;
               $employeeLeave->leaves_taken=$currentLeavesTaken-($application->num_of_days);
               $employeeLeave->remaining_days=($employeeLeave->remaining_days)+($application->num_of_days);
               $employeeLeave->save();
            }
         }
      }

      Session::flash('success',"Leave application cancelled successfully!");
      return redirect()->route('showLeaveApplicationList');
   }
}
