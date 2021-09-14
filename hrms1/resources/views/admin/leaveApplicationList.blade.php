@extends('layouts.app')

<script type="text/javascript">
   function approve() {
      document.getElementById("leaveProcessing").action = "{{ route('approveMultipleLeave') }}";
   }

   function reject() {
      document.getElementById("leaveProcessing").action = "{{ route('rejectMultipleLeave') }}";
   }

   function selectAll() {
      if(document.getElementById("selectAllCheckbox").checked) {
         alert("Hello goodbye");
         var checkboxes = document.getElementsByName("leaveApplication[]");
         foreach

      }
   }
</script>

@section('content')
@if(Session::has('success'))
    <div class="alert alert-success" role="alert">
        {{ Session::get('success')}}
    </div>
@endif

<a href="" class="btn btn-primary disabled">Update status for expired leave applications</a>

<div style="text-align:center">
   <form id="leaveProcessing" action="" method="get">
      @csrf

      <input type="submit" name="submitButton" value="Approve" class="btn btn-success" onclick="approve()">
      <input type="submit" name="submitButton" value="Reject" class="btn btn-warning" onclick="reject()">
      <table>
         <tr>
            <th>
               <input type="checkbox" id="selectAllCheckbox" value="" onchange="selectAll()">
            </th>
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
         @php
            $today=date("Y-m-d");
            $leaveDate=$leaveApplication->start_date;
         @endphp

         <tr>
            <td>
               @if (($today < $leaveDate) && ($leaveApplication->status !== 'Cancelled'))
               <input type="checkbox" name="leaveApplication[]" value="{{ $leaveApplication->id }}">
               @endif
            </td>
            <td>{{ $leaveApplication->id }}</td>
            <td>{{ $leaveApplication->leaveTypeName }}</td>
            <td>{{ $leaveApplication->employee }} {{ $leaveApplication->employeeName }}</td>
            <td>{{ $leaveApplication->start_date }}</td>
            <td>{{ $leaveApplication->end_date }}</td>
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
               @if (($today < $leaveDate) && ($leaveApplication->status !== 'Cancelled'))
                  @if ($leaveApplication->status !== 'Approved')
                  <a href="{{ route('approveLeave', ['employeeId' => $leaveApplication->employee, 'id' => $leaveApplication->id]) }}" class="btn btn-success" >Approve</a>
                  @endif

                  @if ($leaveApplication->status !== 'Rejected')
                  <a href="{{ route('rejectLeave', ['employeeId' => $leaveApplication->employee, 'id' => $leaveApplication->id]) }}" class="btn btn-warning" >Reject</a>
                  @endif
               @else
                  -
               @endif
            </td>
         </tr>
         @endforeach
      </table>
   </form>

</div>
@endsection
