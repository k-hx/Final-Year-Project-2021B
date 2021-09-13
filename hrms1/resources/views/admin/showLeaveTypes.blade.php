@extends('layouts.app')

<link rel="stylesheet" href="{{ asset('css/style2.css') }}">

@section('content')
@if(Session::has('success'))
    <div class="alert alert-success" role="alert">
        {{ Session::get('success')}}
    </div>
@endif

<td><a href="{{ route('createLeaveType') }}" class="btn btn-primary">Create Leave Type</a></td>

<div>
   <table>
      <tr>
         <td>ID</td>
         <td>Leave Type Name</td>
         <td>Minimum Number of Days</td>
         <td>Action</td>
      </tr>

      @foreach($leaveTypes as $leaveType)
      <tr>
         <td>{{$leaveType->id}}</td>
         <td>{{$leaveType->name}}</td>
         <td>{{$leaveType->min_num_of_days}}</td>
         <td><a href="{{ route('editLeaveType', ['id' => $leaveType->id]) }}" class="btn btn-warning" >Edit</a><a href="{{ route('deleteLeaveType', ['id' => $leaveType->id]) }}" class="btn btn-danger" onclick="return confirm('Delete {{$leaveType->name}}?')">Delete</a></td>
      </tr>
      @endforeach
      
   </table>
</div>
@endsection