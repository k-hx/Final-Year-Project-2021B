@extends('layouts.app')
@section('content')
@if(Session::has('success'))
    <div class="alert alert-success" role="alert">
        {{ Session::get('success')}}
    </div>
@endif

<a href="{{ route('showAdminApplyLeavePage') }}" class="btn btn-primary">Apply Leave</a>
<div style="text-align:center">
   <table id="adminOwnLeaveApplicationList">
      <thead>
         <tr>
            <th>Leave Application ID</th>
            <th>Leave Type</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Leave Approver</th>
            <th>Status</th>
            <th>Reason</th>
            <th>File</th>
            <th>Action</th>
         </tr>
      </thead>

      <tbody>
         @foreach($adminLeaveApplications as $adminLeaveApplication)
         <tr>
            <td>{{ $adminLeaveApplication->id }}</td>
            <td>{{ $adminLeaveApplication->leaveTypeName }}</td>
            <td>{{ $adminLeaveApplication->start_date }}</td>
            <td>{{ $adminLeaveApplication->end_date }}</td>
            <td>{{ $adminLeaveApplication->leaveApproverId }} {{ $adminLeaveApplication->leaveApproverName }}</td>
            <td>{{ $adminLeaveApplication->status }}</td>
            <td>{{ $adminLeaveApplication->reason }}</td>
            <td>
               @if ($adminLeaveApplication->document != '')
               <a href="{{ asset('documents/') }}/{{$adminLeaveApplication->document}}" class="link" target="_blank">File</a>
               @else
               -
               @endif
            </td>
            <td>
               @php
                  $today=date("Y-m-d");
                  $leaveDate=$adminLeaveApplication->start_date;
               @endphp

               @if (($today < $leaveDate) && ($adminLeaveApplication->status !== 'Cancelled'))
               <a href="{{ route('cancelLeaveAdmin', ['adminId' => $adminLeaveApplication->admin, 'id' => $adminLeaveApplication->id])}}" class="btn btn-danger" onclick="return confirm('Cancel this leave application?')">Cancel</a>
               @else
               -
               @endif
            </td>

         </tr>
         @endforeach
      </tbody>   
   </table>
</div>
@endsection
