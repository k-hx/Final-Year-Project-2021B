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
   <p class="font-weight-bold">Employee</p>
   @endforeach
</div>


@endsection
