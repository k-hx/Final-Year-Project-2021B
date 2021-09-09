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
      Employee's ID: {{$employee->id}}
   </p>

   <p class="font-weight-bold">
      Employee's Name: {{ $employee->full_name }}
   </p>

   <p class="font-weight-bold">
      Employee's Leave Grade: {{ $employee->leave_grade }}
   </p>

   @if($employee->leave_grade == '-')
   <p>Leave grade is not yet assigned.</p>
   @endif

   <a href="{{ route('showAssignLeaveGradePage',['id' => $employee->id]) }}" class="btn btn-primary">Change Leave Grade</a>

   <table>
      <tr>
         <th>Leave Type ID</th>
         <th>Leave Type Name</th>
         <th>Number of Days Entitled</th>
      </tr>

      @foreach($leaveEntitlements as $leaveEntitlement)
      <tr>
         <td>{{ $leaveEntitlement->leaveTypeId }}</td>
         <td>{{ $leaveEntitlement->leaveTypeName }}</td>
         <td>{{ $leaveEntitlement->num_of_days }}</td>
      </tr>
      @endforeach
      <tr>

      </tr>
   </table>

   @endforeach
</div>


@endsection
