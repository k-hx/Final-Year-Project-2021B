@extends('layouts.app')

<link rel="stylesheet" href="{{ asset('css/style2.css') }}">

@section('content')
@if(Session::has('success'))
    <div class="alert alert-success" role="alert">
        {{ Session::get('success')}}
    </div>
@endif

<div>
   <table>
      <tr>
         <th>ID</th>
         <th>Employee Name</th>
         <th>Leave Grade</th>
         <th>Action</th>
      </tr>

      @foreach($employees as $employee)
      <tr>
         <td>{{ $employee->id }}</td>
         <td>{{ $employee->full_name }}</td>
         <td>{{ $employee->leave_grade }}</td>
         <td>
            <a href="{{ route('employeesLeaveGrade', ['id' => $employee->id]) }}" class="btn btn-primary">Leave Grade</a>
         </td>
      </tr>
      @endforeach

   </table>

   <h1>Add a refresh button</h1>
</div>


@endsection
