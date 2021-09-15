@extends('layouts.app')

<script type="text/javascript">
function selectAll() {
   var checkboxes = document.getElementsByName("leaveApplication[]");

   if(document.getElementById("selectAllCheckbox").checked) {
      //check all checkboxes
      for(var i=0; i<checkboxes.length; i++) {
         checkboxes[i].checked = true;
      }
   } else {
      //uncheck all checkboxes
      for(var i=0; i<checkboxes.length; i++) {
         checkboxes[i].checked = false;
      }
   }
}   
</script>

@section('content')
@if(Session::has('success'))
    <div class="alert alert-success" role="alert">
        {{ Session::get('success')}}
    </div>
@endif

<a href="{{ route('showApplyLeavePage') }}" class="btn btn-primary">Apply Leave</a>
<div style="text-align:center">
   <form class="" action="{{ route('cancelMultiple') }}" method="get">
      <input type="submit" name="cancelButton" value="Cancel" class="btn btn-success">

      <table>
         <tr>
            <th>
               <input type="checkbox" id="selectAllCheckbox" value="" onchange="selectAll()">
            </th>
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
         @php
            $today=date("Y-m-d");
            $leaveDate=$leaveApplication->start_date;
         @endphp

         <tr>
            <td>
               @if($today < $leaveDate)
               <input type="checkbox" name="leaveApplication[]" value="{{ $leaveApplication->id }}">
               @endif
            </td>
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
               <a href="{{ route('cancelLeave', ['employeeId' => $leaveApplication->employee, 'id' => $leaveApplication->id])}}" class="btn btn-danger" onclick="return confirm('Cancel this leave application?')">Cancel</a>
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
