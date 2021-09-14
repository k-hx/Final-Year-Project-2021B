@extends('layouts.app')

<link rel="stylesheet" href="{{ asset('css/style2.css') }}">

@section('content')
<script type="text/javascript">

function checkAndCalculate() {

   //get selected leave type
   var selectedLeaveType = document.getElementById("leaveType");
   var result = selectedLeaveType.options[selectedLeaveType.selectedIndex].value;

   //get remaining days for the leave type
   var adminLeaveRemainingDays = 0;
   @foreach($adminLeaves as $adminLeave)
   var leaveType = "{{ $adminLeave->leave_type }}";
   if(result === leaveType) {
      adminLeaveRemainingDays = {{ $adminLeave->remaining_days }};
   }
   @endforeach

   var pendingLeaveApplicationDays = 0;
   @foreach($leaveApplications as $leaveApplication)
   var applicationLeaveType = {{ $leaveApplication->leave_type_id }};
   if(result == applicationLeaveType) {
         pendingLeaveApplicationDays = pendingLeaveApplicationDays + {{ $leaveApplication->num_of_days }};
   }
   @endforeach

   var remainingAfterPending = adminLeaveRemainingDays - pendingLeaveApplicationDays;

   if(adminLeaveRemainingDays === 0) {
      document.getElementById("startDate").value = "";
      document.getElementById("endDate").value = "";
      alert("You have no remaining leave entitlement for this leave type! \nPlease apply for unpaid leaves for the exceeded days!");
   } else {
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

         //if users have not select any of the dates
         if(isNaN(dayDifference)) {
            dayDifference = 0;
         }

         if(dayDifference > adminLeaveRemainingDays) {
            //compare remaining days with day difference
            document.getElementById("endDate").value = "";
            alert("Your remaining leave entitlement for the leave type is not enough! \nPlease apply for unpaid leaves for the exceeded days!");
         } else if(dayDifference > remainingAfterPending) {
            //compare remaining days + total days of pending leave application for the leave type with day difference
            document.getElementById("endDate").value = "";
            alert("The total days of your pending leave application and current leave application exceed the remaining days for the leave type!")
         } else {
            document.getElementById("numOfDays").value = dayDifference;
         }
      }
   }
}

function changeAdminLeave() {
   var selectedLeaveType = document.getElementById("leaveType");
   var result = selectedLeaveType.options[selectedLeaveType.selectedIndex].value;

   document.getElementById("leaveInformation").hidden = false;

   @foreach($adminLeaves as $adminLeave)
   if("{{ $adminLeave->leave_type }}" === result) {
      document.getElementById("adminLeaveLeaveType").innerHTML="{{ $adminLeave->leave_type }}";
      document.getElementById("adminLeaveLeaveTypeName").innerHTML="{{ $adminLeave->leaveTypeName }}";
      document.getElementById("adminLeaveTotalDays").innerHTML="{{ $adminLeave->total_days }}";
      document.getElementById("adminLeaveLeavesTaken").innerHTML="{{ $adminLeave->leaves_taken }}";
      document.getElementById("adminLeaveRemainingDays").innerHTML="{{ $adminLeave->remaining_days }}";
   }
   @endforeach
}

</script>

<h1>Leave Application</h1>
<div style="text-align:center">
   <form method="post" action="{{ route('admin/submitApplication') }}" enctype="multipart/form-data" onsubmit="return validate()">
      @csrf

      <p>
         <label for="leaveType" class="label">Leave Type</label>
         <select class="form-control" name="leaveTypeId" id="leaveType" onchange="changeAdminLeave();checkAndCalculate()" required>
            @foreach($adminLeaves as $adminLeave)
            <option value="{{ $adminLeave->leave_type }}">{{ $adminLeaves->leaveTypeName}}</option>
            @endforeach
         </select>
      </p>

      <table style="margin:auto;text-align:center;" id="leaveInformation">
         <tr>
            <th>Leave Type ID</th>
            <th>Leave Type Name</th>
            <th>Total Days</th>
            <th>Number of Leaves Taken</th>
            <th>Remaining Days</th>
         </tr>

         @php $number=0; @endphp
         @foreach($adminLeaves as $adminLeaves)
         @if($number==0)
            @php $number=$number+1; @endphp
            <tr>
               <td id="adminLeaveLeaveType">{{ $adminLeave->leave_type }}</td>
               <td id="adminLeaveLeaveTypeName">{{ $adminLeave->leaveTypeName }}</td>
               <td id="adminLeaveTotalDays">{{ $adminLeave->total_days }}</td>
               <td id="adminLeaveLeavesTaken">{{ $adminLeave->leaves_taken }}</td>
               <td id="adminLeaveRemainingDays">{{ $adminLeave->remaining_days }}</td>
            </tr>
         @endif
         @endforeach
      </table>

      @foreach($admins as $admin)
      <input type="hidden" name="admin" value="{{ $adminLeave->id }}">
      <input type="hidden" name="leaveApprover" value="{{ $admin->supervisor }}">
      @endforeach

      <p>
         <label for="startDate" class="label">Start date</label>
         <input type="date" name="startDate" id="startDate" required onchange="checkAndCalculate()">
      </p>

      <p>
         <label for="endDate" class="label">End date</label>
         <input type="date" name="endDate" id="endDate" required onchange="checkAndCalculate()">
      </p>

      <input type="hidden" id="numOfDays" name="numOfDays" value="">

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
