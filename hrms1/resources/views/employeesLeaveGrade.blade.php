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

   <h1>Show leave entitlements for the leave grade here</h1>

   @endforeach
</div>


@endsection
