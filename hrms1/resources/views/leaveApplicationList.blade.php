@extends('layouts.app')
@section('content')
@if(Session::has('success'))
    <div class="alert alert-success" role="alert">
        {{ Session::get('success')}}
    </div>
@endif

<a href="{{ route('showApplyLeavePage') }}" class="btn btn-info">Apply Leave</a>
<div style="text-align:center">
   <table>
      <tr>
         <th>Leave Application ID</th>
         <th>Leave Type</th>
         <th>Start Date Time</th>
         <th>End Date Time</th>
         <th>Leave Approver</th>
         <th>Status</th>
         <th>Reason</th>
         <th>File</th>
         <th>Action</th>
      </tr>

      @foreach($leaveApplications as $leaveApplication)
      <tr>
         <td>{{ $leaveApplication->id }}</td>
         <td>{{ $leaveApplication->leaveTypeName }}</td>
         <td>{{ $leaveApplication->start_date_time }}</td>
         <td>{{ $leaveApplication->end_date_time }}</td>
         <td>{{ $leaveApplication->leaveApproverId }} {{ $leaveApplication->leaveApproverName }}</td>
         <td>{{ $leaveApplication->status }}</td>
         <td>{{ $leaveApplication->reason }}</td>
         <td>
            @if ($leaveApplication->document != '')
            <a href="{{ asset('documents/') }}/{{$leaveApplication->document}}" class="link" target="_blank">File</a>
            @else
            -
            @endif
         </td>
         <td>
            @if ($leaveApplication->status !== 'Leave Taken')
            <a href="{{ route('cancelLeave', ['id' => $leaveApplication->id])}}" class="btn btn-danger">Cancel</a>
            @endif
         </td>

      </tr>
      @endforeach

   </table>
</div>
@endsection
