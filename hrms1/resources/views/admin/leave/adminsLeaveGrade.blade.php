@extends('layouts.app')

<link rel="stylesheet" href="{{ asset('css/style2.css') }}">

@section('content')
@if(Session::has('success'))
    <div class="alert alert-success" role="alert">
        {{ Session::get('success')}}
    </div>
@endif

<h1>Admin's Leave Grade</h1>

<div>
   @foreach($admins as $admin)
   <p class="font-weight-bold">
      Admin's ID: {{ $admin->id }}
   </p>

   <p class="font-weight-bold">
      Admin's Name: {{ $admin->full_name }}
   </p>

   <p class="font-weight-bold">
      Admin's Leave Grade: {{ $admin->leave_grade }} {{ $admin->leaveGradeName }}
   </p>

   @if($admin->leave_grade == 'Unassigned')
   <p>Leave grade is not yet assigned.</p>
   @endif

   <a href="{{ route('setAdminsLeaveGrade', ['id' => $admin->id]) }}" class="btn btn-primary">Change Leave Grade</a>

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

      @foreach($adminLeaves as $adminLeave)
      <tr>
         <td>{{ $adminLeave->leave_type }}</td>
         <td>{{ $adminLeave->leaveTypeName }}</td>
         <td>{{ $adminLeave->total_days }}</td>
         <td>{{ $adminLeave->leaves_taken }}</td>
         <td>{{ $adminLeave->remaining_days }}</td>
         <td>{{ $adminLeave->year }}</td>
         <td>{{ $adminLeave->status }}</td>
      </tr>
      @endforeach
      <tr>

      </tr>
   </table>

   @endforeach
</div>


@endsection
