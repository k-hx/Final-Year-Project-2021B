@extends('layouts.app')

<link rel="stylesheet" href="{{ asset('css/style2.css') }}">

@section('content')
@if(Session::has('success'))
    <div class="alert alert-success" role="alert">
        {{ Session::get('success')}}
    </div>
@endif

<script type="text/javascript">
   function validate() {
      @foreach($admins as $admin)
      var selectedLeaveGrade = document.getElementById("adminsLeaveGrade");
      var result = selectedLeaveGrade.options[selectedLeaveGrade.selectedIndex].value;
      var currentLeaveGrade = {{ $admin->leave_grade }};

      if (result == currentLeaveGrade) {
         alert('The selected leave grade is the same as the current leave grade.');
         return false;
      }
      @endforeach
   }
</script>


<div>
   @foreach($admins as $admin)
      <form action="{{ route('updateAdminsLeaveGrade') }}" method="post" onsubmit="return validate()">
         @csrf
         <p>
            <label for="adminId">Admin ID</label>
            <input type="text" name="admin" value="{{ $admin->id }}" class="form-control" readonly>
         </p>

         <p>
            <label for="adminName">Admin Name</label>
            <input type="text" name="full_name" value="{{ $admin->full_name }}" class="form-control" readonly>
         </p>

         <p>
            <label for="adminsLeaveGrade">Admin's Leave Grade</label>
            <select class="form-control" id="adminsLeaveGrade" name="leave_grade">
               @foreach($leaveGrades as $leaveGrade)
                  @if($leaveGrade->status !== 'Deleted')
                  <option value="{{ $leaveGrade->id }}"
                     @if($leaveGrade->id == $admin->leave_grade)
                     selected
                     @endif>
                     {{ $leaveGrade->name }}
                  </option>
                  @endif
               @endforeach
            </select>
         </p>

         <input type="hidden" name="originalLeaveGrade" value="{{ $admin->leave_grade }}">

         <input type="submit" name="assignButton" value="Assign">
      </form>
      @endforeach
</div>


@endsection
