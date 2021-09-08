@extends('layouts.app')

<link rel="stylesheet" href="{{ asset('css/style2.css') }}">

@section('content')
@if(Session::has('success'))
    <div class="alert alert-success" role="alert">
        {{ Session::get('success')}}
    </div>
@endif

<div>
   @foreach($employees as $employee)
   <form action="index.html" method="post">
      @csrf
      <p>
         <label for="employeeId">Employee ID</label>
         <input type="text" name="employeeId" value="{{ $employee->id }}" class="form-control" readonly>
      </p>

      <p>
         <label for="employeeName">Employee Name</label>
         <input type="text" name="employeeName" value="{{ $employee->name }}" class="form-control" readonly>
      </p>

      <p>
         <label for="employeesLeaveGrade">Employee's Leave Grade</label>
         <select class="form-control" name="employeesLeaveGrade">
            @foreach($leaveGrades as @leaveGrade)
            <option value="{{ $leaveGrade->id }}" @if({{ $leaveGrade->id }} == {{ $employee->leaveGrade }}) selected @endif>{{ $leaveGrade->name }}</option>
            @endforeach
         </select>

      </p>
      <input type="submit" name="" value="Assign">
   </form>
   @endforeach
</div>


@endsection
