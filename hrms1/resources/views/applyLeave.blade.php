@extends('layouts.app')

<link rel="stylesheet" href="{{ asset('css/style2.css') }}">

@section('content')
<script type="text/javascript">

function checkAndCalculate() {
   var currentDate = new Date();

   var startDateInput = document.getElementById("startDate").value;
   var startDate = Date.parse(startDateInput);

   var endDateInput = document.getElementById("endDate").value;
   var endDate = Date.parse(endDateInput);

   if(currentDate > startDate) {
      alert("The start date cannot be later than today's date!");
      document.getElementById("startDate").value = "";
   } else if(currentDate > endDate) {
      alert("The end date cannot be later than today's date!");
      document.getElementById("endDate").value = "";
   } else if(startDate > endDate) {
      alert("The end date cannot be earlier than the start date!");
      document.getElementById("endDate").value = "";
   } else {
      var difference = endDate - startDate;
      var dayDifference = (difference / (1000 * 60 * 60 * 24)) + 1;
      if(isNaN(dayDifference)) {
         dayDifference = 0;
      }
      alert(dayDifference);
   }
}

function changeEmployeeLeave() {
   var selectedLeaveType = document.getElementById("leaveType");
   var result = selectedLeaveType.options[selectedLeaveType.selectedIndex].value;

   @foreach($employeeLeaves as $employeeLeave)

   @endforeach

}

</script>

<h1>Leave Application</h1>
<div style="text-align:center">
   <form method="post" action="{{ route('submitApplication') }}" enctype="multipart/form-data" onsubmit="return validate()">
      @csrf

      <p>
         <label for="leaveType" class="label">Leave Type</label>
         <select class="form-control" name="leaveTypeId" id="leaveType" onchange="changeEmployeeLeave()" required>
            @foreach($employeeLeaves as $employeeLeave)
            <option value="{{ $employeeLeave->leave_type }}">{{ $employeeLeave->leaveTypeName}}</option>
            @endforeach
         </select>
      </p>

      <table style="margin:auto;text-align:center;">
         <tr>
            <th>Leave Type ID</th>
            <th>Leave Type Name</th>
            <th>Total Days</th>
            <th>Number of Leaves Taken</th>
            <th>Remaining Days</th>
         </tr>

         @php $number=0; @endphp
         @foreach($employeeLeaves as $employeeLeave)
         @if($number==0)
            @php $number=$number+1; @endphp
            <tr>
               <td id="employeeLeaveId">{{ $employeeLeave->id }}</td>
               <td>{{ $employeeLeave->leaveTypeName }}</td>
               <td>{{ $employeeLeave->total_days }}</td>
               <td>{{ $employeeLeave->leaves_taken }}</td>
               <td>{{ $employeeLeave->remaining_days }}</td>
            </tr>
         @endif
         @endforeach

      </table>

      @foreach($employees as $employee)
      <input type="hidden" name="employee" value="{{ $employee->id }}">
      <input type="hidden" name="leaveApprover" value="{{ $employee->supervisor }}">
      @endforeach

      <p>
         <label for="startDate" class="label">Start date</label>
         <input type="date" name="startDate" id="startDate" required onchange="checkAndCalculate()">
      </p>

      <p>
         <label for="endDate" class="label">End date</label>
         <input type="date" name="endDate" id="endDate" required onchange="checkAndCalculate()">
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
