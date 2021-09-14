@extends('layouts.app')

<link rel="stylesheet" href="{{ asset('css/style2.css') }}">

@section('content')
@if(Session::has('success'))
    <div class="alert alert-success" role="alert">
        {{ Session::get('success')}}
    </div>
@endif

<h1>Employee's Leave Grade</h1>

<div>
   @foreach($employees as $employee)
   <p class="font-weight-bold">
      Employee's ID: {{ $employee->id }}
   </p>

   <p class="font-weight-bold">
      Employee's Name: {{ $employee->full_name }}
   </p>

   <p class="font-weight-bold">
      Employee's Leave Grade: {{ $employee->leave_grade }} {{ $employee->leaveGradeName }}
   </p>

   @if($employee->leave_grade == 'Unassigned')
   <p>Leave grade is not yet assigned.</p>
   @endif

   <a href="{{ route('setEmployeesLeaveGrade', ['id' => $employee->id]) }}" class="btn btn-primary">Change Leave Grade</a>

   <table>
      <tr>
         <th>Leave Type ID</th>
         <th>Leave Type Name</th>
         <th>Number of Days Entitled</th>
         <th>Leaves Taken</th>
         <th>Remaining Days</th>
         <th>Year</th>
         <th>Status</th>
      </tr>

      @foreach($employeeLeaves as $employeeLeave)
      <tr>
         <td>{{ $employeeLeave->leave_type }}</td>
         <td>{{ $employeeLeave->leaveTypeName }}</td>
         <td>{{ $employeeLeave->total_days }}</td>
         <td>{{ $employeeLeave->leaves_taken }}</td>
         <td>{{ $employeeLeave->remaining_days }}</td>
         <td>{{ $employeeLeave->year }}</td>
         <td>{{ $employeeLeave->status }}</td>
      </tr>
      @endforeach
      <tr>

      </tr>
   </table>

   @endforeach
</div>


@endsection
