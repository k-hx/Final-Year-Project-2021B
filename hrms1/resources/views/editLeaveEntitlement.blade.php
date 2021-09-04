@extends('layouts.app')
@section('content')
<div style="text-align:center">
   <form method="post" action="{{ route('addLeaveGrade') }}" enctype="multipart/form-data">
      @csrf
      <p>
         <label for="name" class="label">Leave Grade Name</label>
         <input type="text" name="name" id="name">
      </p>

      <p>{{ $id }}</p>

      <!--Edit here-->
      @foreach($leaveTypes as $leaveType)
      <p>
         <label for="{{ $leaveType->name }}" class="label">{{ $leaveType->name }}</label>
         <input type="number" name="{{ $leaveType->name }}" id="{{ $leaveType->name }}">
      </p>
      @endforeach

      <p>
         <input type="submit" name="create" value="Create">
      </p>
   </form>
</div>
@endsection
