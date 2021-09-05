@extends('layouts.app')

<link rel="stylesheet" href="{{ asset('css/style2.css') }}">

@section('content')
<div style="text-align:center">
   <form method="post" action="{{ route('updateLeaveGradeName') }}" enctype="multipart/form-data">
      @csrf

      @foreach($leaveGrades as $leaveGrade)
      <p>
         <label for="id" class="label">ID</label>
         <input type="text" name="id" id="id" value="{{ $leaveGrade->id}}" readonly>
      </p>
      <p>
         <label for="name" class="label">Leave Type Name</label>
         <input type="text" name="name" id="name" value="{{ $leaveGrade->name}}">
      </p>

      @endforeach

      <p>
         <input type="submit" name="create" value="Update">
      </p>
   </form>
</div>
@endsection
