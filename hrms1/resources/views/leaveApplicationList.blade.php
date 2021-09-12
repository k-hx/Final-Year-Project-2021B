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
         <th>Start Date</th>
         <th>End Date</th>
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
         <td>{{ $leaveApplication->start_date }}</td>
         <td>{{ $leaveApplication->end_date }}</td>
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
            @php
               $today=date("Y-m-d");
               $leaveDate=$leaveApplication->start_date;
            @endphp

            @if (($today < $leaveDate) && ($leaveApplication->status !== 'Cancelled'))
            <a href="{{ route('cancelLeave', ['id' => $leaveApplication->id])}}" class="btn btn-danger" onclick="return confirm('Cancel this leave application?')">Cancel</a>
            @else
            -
            @endif
         </td>

      </tr>
      @endforeach

   </table>
</div>
@endsection
