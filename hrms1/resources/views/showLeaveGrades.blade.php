@extends('layouts.app')

<link rel="stylesheet" href="{{ asset('css/style2.css') }}">

@section('content')
@if(Session::has('success'))
    <div class="alert alert-success" role="alert">
        {{ Session::get('success')}}
    </div>
@endif

<td><a href="{{ route('createLeaveGrade') }}" class="btn btn-primary">Create Leave Grade</a></td>

<div>
   <table>
      <tr>
         <td>ID</td>
         <td>Leave Grade Name</td>
         <td>Action</td>
      </tr>

      @foreach($leaveGrades as $leaveGrade)
      <tr>
         <td>{{$leaveGrade->id}}</td>
         <td>{{$leaveGrade->name}}</td>
         <td>
            <a href="{{ route('editLeaveGradeName', ['id' => $leaveGrade->id]) }}" class="btn btn-warning" >Edit Leave Grade Name</a>
            <a href="#" class="btn btn-info" >Edit Leave Leave Entitlement</a>
            <a href="{{ route('deleteLeaveGrade', ['id' => $leaveGrade->id]) }}" class="btn btn-danger" onclick="return confirm('Delete {{$leaveGrade->name}}?')">Delete</a>
         </td>
      </tr>
      @endforeach

   </table>
</div>
@endsection
