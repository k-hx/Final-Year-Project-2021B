@extends('layouts.app')
@section('content')
@if(Session::has('success'))
    <div class="alert alert-success" role="alert">
        {{ Session::get('success')}}
    </div>
@endif

<div style="text-align:center">
   <table>
      <tr>
         <th>Leave Application ID</th>
         <th>Leave Type</th>
         <th>Employee</th>
         <th>Start Date Time</th>
         <th>End Date Time</th>
         <th>Status</th>
         <th>Reason</th>
         <th>File</th>
         <th>Action</th>
      </tr>

      @foreach($leaveApplications as $leaveApplication)
      <tr>
         <td>{{ $leaveApplication->id }}</td>
         <td>{{ $leaveApplication->leaveTypeName }}</td>
         <td>{{ $leaveApplication->employee }} {{ $leaveApplication->employeeName }}</td>
         <td>{{ $leaveApplication->start_date_time }}</td>
         <td>{{ $leaveApplication->end_date_time }}</td>
         <td>{{ $leaveApplication->status }}</td>
         <td>{{ $leaveApplication->reason }}</td>
         <td>
            @if ($leaveApplication->document != '')
            <a href="{{ asset('documents/') }}/{{$leaveApplication->document}}" class="link">File</a>
            @else
            -
            @endif
         </td>
         <td>
            @if ($leaveApplication->status !== 'Leave Taken')
               @if ($leaveApplication->status !== 'Approved')
               <form class="" action="{{ route('approveLeave', ['id' => $leaveApplication->id]) }}" method="post">
                  @csrf
                  <input type="submit" name="approve" value="Approve" class="btn btn-success">
               </form>
               @endif

               @if ($leaveApplication->status !== 'Rejected')
               <form class="" action="{{ route('rejectLeave', ['id' => $leaveApplication->id]) }}" method="post">
                  @csrf
                  <input type="submit" name="reject" value="Reject" class="btn btn-warning">
               </form>
               @endif
            @endif
         </td>
      </tr>
      @endforeach

   </table>
</div>
@endsection
