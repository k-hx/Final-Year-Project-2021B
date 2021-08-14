@extends('layouts.app')
@section('content')
<div style="text-align:center">
   <form method="post" action="{{ route('addLeaveType') }}" enctype="multipart/form-data">
      @csrf
      <p>
         <label for="name" class="label">Leave Type Name</label>
         <input type="text" name="name" id="name">
      </p>

      <p>
         <input type="submit" name="create" value="Create">
      </p>
   </form>
</div>
@endsection
