@extends('layouts.app')

<link rel="stylesheet" href="{{ asset('css/style2.css') }}">

@section('content')
@if(Session::has('success'))
    <div class="alert alert-success" role="alert">
        {{ Session::get('success')}}
    </div>
@endif

@if(Session::has('primary'))
    <div class="alert alert-primary" role="alert">
        {{ Session::get('primary')}}
    </div>
@endif

@if(Session::has('danger'))
    <div class="alert alert-danger" role="alert">
        {{ Session::get('danger')}}
    </div>
@endif

<a href="{{ route('createLeaveRecord') }}" class="btn btn-primary">Refresh</a>

<div>
   <table id="allEmployeesLeaveGradeTable">
      <thead>
         <tr>
            <th>ID</th>
            <th>Employee Name</th>
            <th>Leave Grade</th>
            <th>Action</th>
         </tr>
      </thead>

      <tbody>
         @foreach($employees as $employee)
         <tr>
            <td>{{ $employee->id }}</td>
            <td>{{ $employee->full_name }}</td>
            <td>{{ $employee->leave_grade }} {{ $employee->leaveGradeName }}</td>
            <td>
               <a href="{{ route('employeesLeaveGrade', ['id' => $employee->id]) }}" class="btn btn-primary">View/Edit Leave Information</a>
            </td>
         </tr>
         @endforeach
      </tbody>

   </table>

</div>


@endsection
