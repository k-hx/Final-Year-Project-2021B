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
         <td>ID</td>
         <td>Leave Type Name</td>
         <td>Minimum Number of Days</td>
      </tr>

      @foreach($leaveTypes as $leaveType)
      <tr>
         <td>{{$leaveType->id}}</td>
         <td>{{$leaveType->name}}</td>
         <td>{{$leaveType->min_num_of_days}}</td>
      </tr>
      @endforeach
   </table>
</div>
@endsection
