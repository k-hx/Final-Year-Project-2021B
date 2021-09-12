@extends('layouts.app')

<link rel="stylesheet" href="{{ asset('css/style2.css') }}">

@section('content')
<script type="text/javascript">

</script>

<h1>Leave Application</h1>
<div style="text-align:center">
   <form method="post" action="{{ route('submitApplication') }}" enctype="multipart/form-data">
      @csrf

      <p>
         <label for="leaveType" class="label">Leave Type</label>
         <select class="form-control" name="leaveTypeId" required>
            @foreach($employeeLeaves as $employeeLeave)
            <option value="{{ $employeeLeave->leave_type }}">{{ $employeeLeave->leaveTypeName}}</option>
            @endforeach
         </select>
      </p>

      <table style="margin:auto;text-align:center;">
         <tr>
            <th>Leave Type ID</th>
            <th>Total Days</th>
            <th>Number of Leaves Taken</th>
            <th>Remaining Days</th>
         </tr>

         @foreach($employeeLeaves as $employeeLeave)
         <tr id="rowFor{{ $employeeLeave->id }}">
            <td id="{{ $employeeLeave->id }}">{{ $employeeLeave->id }}</td>
            <td>{{ $employeeLeave->total_days }}</td>
            <td>{{ $employeeLeave->leaves_taken }}</td>
            <td>{{ $employeeLeave->remaining_days }}</td>
         </tr>
         @endforeach

      </table>

      @foreach($employees as $employee)
      <input type="hidden" name="employee" value="{{ $employee->id }}">
      <input type="hidden" name="leaveApprover" value="{{ $employee->supervisor }}">
      @endforeach

      <p>
         <label for="startDate" class="label">Start date</label>
         <input type="date" name="startDate" id="startDate" required>
      </p>

      <p>
         <label for="endDate" class="label">End date</label>
         <input type="date" name="endDate" id="endDate" required>
      </p>

      <p>
         <label for="reason" class="label">Reason</label>
         <textarea name="reason" rows="8" cols="30" required></textarea>
      </p>

      <p>
         <label for="document" class="label">Document</label>
         <input type="file" class="form-control" name="document" value="">
      </p>

      <p>
         <input type="submit" name="create" value="Apply">
      </p>

   </form>
</div>
@endsection
