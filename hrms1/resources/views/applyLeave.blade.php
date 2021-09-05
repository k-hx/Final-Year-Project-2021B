@extends('layouts.app')
@section('content')
<div style="text-align:center">
   <form method="post" action="{{ route('applyLeave') }}" enctype="multipart/form-data">
      @csrf

      @foreach($employees as $employee)
      <p>
         <label for="leaveType" class="label">Leave Type</label>
         <input type="text" name="leaveType" id="leaveType">
      </p>

      <input type="hidden" name="employee" value="{{ $employee->id }}"
      <input type="hidden" name="leaveApprover" value="{{ $employee->supervisor}}">

      <p>
         <label for="min_num_of_days" class="label">Minimum Number of Days</label>
         <input type="number" name="min_num_of_days" id="min_num_of_days">
      </p>

      <p>
         <input type="submit" name="create" value="Create">
      </p>
      @endforeach
   </form>
</div>
@endsection
