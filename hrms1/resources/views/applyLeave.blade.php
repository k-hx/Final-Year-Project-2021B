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
         <label for="startDateTime" class="label">Start date time</label>
         <input type="datetime-local" name="startDateTime" id="startDateTime">
      </p>

      <p>
         <label for="endDateTime" class="label">End date time</label>
         <input type="datetime-local" name="endDateTime" id="endDateTime">
      </p>

      <p>
         <label for="reason" class="label">Reason</label>
         <textarea name="reason" rows="8" cols="20"></textarea>
      </p>

      <p>
         <input type="submit" name="create" value="Create">
      </p>
      @endforeach
   </form>
</div>
@endsection
