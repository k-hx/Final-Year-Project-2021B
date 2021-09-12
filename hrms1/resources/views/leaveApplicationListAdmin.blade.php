@extends('layouts.app')
@section('content')
@if(Session::has('success'))
    <div class="alert alert-success" role="alert">
        {{ Session::get('success')}}
    </div>
@endif

<script type="text/javascript">

</script>

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

      @php $number=0; @endphp
      @foreach($leaveApplications as $leaveApplication)
      @php
         $number+=1;
         $string1="form";
         $string2=strval($number);
         $string3=$string1.$string2;
         echo $string3;
      @endphp

      <form id=$string3 action="" method="get">
         <tr>
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
               @if ($leaveApplication->status !== 'Leave Taken' && $leaveApplication->status !== 'Cancelled')
                  @if ($leaveApplication->status !== 'Approved')
                  <!-- <a href="{{ route('approveLeave', ['id' => $leaveApplication->id]) }}" class="btn btn-success" >Approve</a> -->
                  <input type="submit" name="approve" value="Approve" class="btn btn-success" on="approve()">
                  @endif

                  @if ($leaveApplication->status !== 'Rejected')
                  <!-- <a href="{{ route('rejectLeave', ['id' => $leaveApplication->id]) }}" class="btn btn-warning" >Reject</a> -->
                  <input type="submit" name="reject" value="Reject" class="btn btn-warning" onclick="reject({{ $leaveApplication->id }})">
                  @endif
               @else
                  -
               @endif
            </td>
         </tr>
      </form>
      @endforeach

   </table>
</div>
@endsection
