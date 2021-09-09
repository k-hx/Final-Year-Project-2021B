@extends('layouts.app')

<link rel="stylesheet" href="{{ asset('css/style2.css') }}">

@section('content')
@if(Session::has('success'))
    <div class="alert alert-success" role="alert">
        {{ Session::get('success')}}
    </div>
@endif

<script type="text/javascript">

function validate() {
   var selectedLeaveGrade = document.getElementById("employeesLeaveGrade");
   var result = selectedLeaveGrade.options[selectedLeaveGrade.selectedIndex].value;
   alert(result);

   @foreach($employees as $employee)
   alert("{{ $employee->leave_grade }}");
   @endforeach
}

</script>

<div>
   @foreach($employees as $employee)
   <form action="index.html" method="post" onsubmit="validate()">
      @csrf
      <p>
         <label for="employeeId">Employee ID</label>
         <input type="text" name="employeeId" value="{{ $employee->id }}" class="form-control" readonly>
      </p>

      <p>
         <label for="employeeName">Employee Name</label>
         <input type="text" name="employeeName" value="{{ $employee->full_name }}" class="form-control" readonly>
      </p>

      <p>
         <label for="employeesLeaveGrade">Employee's Leave Grade</label>
         <select class="form-control" id="employeesLeaveGrade" name="employeesLeaveGrade">
            @foreach($leaveGrades as $leaveGrade)
            <option value="{{ $leaveGrade->id }}" @if($leaveGrade->id == $employee->leaveGrade) selected @endif>{{ $leaveGrade->name }}</option>
            @endforeach
         </select>

      </p>
      <input type="submit" name="" value="Assign">
   </form>
   @endforeach
</div>


@endsection
