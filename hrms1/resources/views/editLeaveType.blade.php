@extends('layouts.app')

<link rel="stylesheet" href="{{ asset('css/style2.css') }}">

@section('content')
<div style="text-align:center">
   <form method="post" action="{{ route('updateLeaveType') }}" enctype="multipart/form-data">
      @csrf

      @foreach($leaveTypes as $leaveType)
      <p>
         <label for="id" class="label">ID</label>
         <input type="text" name="id" id="id" value="{{ $leaveType->id}}" readonly>
      </p>
      <p>
         <label for="name" class="label">Leave Type Name</label>
         <input type="text" name="name" id="name" value="{{ $leaveType->name}}">
      </p>

      <p>
         <label for="min_num_of_days" class="label">Minimum Number of Days</label>
         <input type="number" name="min_num_of_days" id="min_num_of_days" value="{{ $leaveType->min_num_of_days }}">
      </p>
      @endforeach

      <p>
         <input type="submit" name="create" value="Update">
      </p>
   </form>
</div>
@endsection
