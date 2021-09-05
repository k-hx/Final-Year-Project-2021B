@extends('layouts.app')
@section('content')

<h1>Leave Application</h1>
<div style="text-align:center">
   <form method="post" action="{{ route('submitApplication') }}" enctype="multipart/form-data">
      @csrf

      @foreach($employees as $employee)
      <p>
         <label for="leaveType" class="label">Leave Type</label>
         <select class="form-control" name="leaveType" required>
            @foreach($leaveTypes as $leaveType)
            <option value="{{ $leaveType->id }}">{{ $leaveType-> name}}</option>
            @endforeach
         </select>
      </p>

      <p>
         <label for="leaveBalance" style="color:red;">Leave Balance</label>
      </p>

      <input type="hidden" name="employee" value="{{ $employee->id }}">
      <input type="hidden" name="leaveApprover" value="{{ $employee->supervisor }}">

      <p>
         <label for="startDateTime" class="label">Start date time</label>
         <input type="datetime-local" name="startDateTime" id="startDateTime" required>
      </p>

      <p>
         <label for="endDateTime" class="label">End date time</label>
         <input type="datetime-local" name="endDateTime" id="endDateTime" required>
      </p>

      <p>
         <label for="reason" class="label">Reason</label>
         <textarea name="reason" rows="8" cols="20" required></textarea>
      </p>

      <p>
         <label for="document" class="label">Document</label>
         <input type="file" class="form-control" name="document" value="">
      </p>

      <p>
         <input type="submit" name="create" value="Apply">
      </p>
      @endforeach
   </form>
</div>
@endsection
