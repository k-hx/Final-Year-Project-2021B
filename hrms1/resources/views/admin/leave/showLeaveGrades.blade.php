@extends('layouts.app')

<link rel="stylesheet" href="{{ asset('css/style2.css') }}">

@section('content')
@if(Session::has('success'))
<div class="alert alert-success" role="alert">
   {{ Session::get('success')}}
</div>
@endif

<script type="text/javascript">
function validate(leaveGradeId) {
   var number=0;

   @foreach($employees as $employee)
   $employeeLeaveGrade={{ $employee->leave_grade }}
   if($employeeLeaveGrade==leaveGradeId) {
      number=number+1;
   }
   @endforeach

   if(number!=0) {
      alert('The leave grade is assigned to at least an employee!');
      return false;
   } else {
      var leaveGradeName="";
      @foreach($leaveGrades as $leaveGrade)
      if({{ $leaveGrade->id }}==leaveGradeId) {
         leaveGradeName="{{ $leaveGrade->name }}";
      }
      @endforeach

      return confirm("Delete " + leaveGradeName + "?");
   }
}
</script>

<a href="{{ route('createLeaveGrade') }}" class="btn btn-primary">Create Leave Grade</a>

<div>

   <table id="leaveGradeTable">
      <thead>
         <tr>
            <td>ID</td>
            <td>Leave Grade Name</td>
            <td>Action</td>
         </tr>
      </thead>

      <tbody>
         @foreach($leaveGrades as $leaveGrade)
         <tr>
            <td>{{$leaveGrade->id}}</td>
            <td>{{$leaveGrade->name}}</td>
            <td>
               <a href="{{ route('editLeaveGradeName', ['id' => $leaveGrade->id]) }}" class="btn btn-warning" >Edit Leave Grade Name</a>
               <a href="{{ route('leaveEntitlement', ['id' => $leaveGrade->id]) }}" class="btn btn-info" >Leave Entitlement</a>
               <a href="{{ route('deleteLeaveGrade', ['id' => $leaveGrade->id]) }}" class="btn btn-danger" onclick="return validate({{ $leaveGrade->id }})" onclick="">Delete</a>
            </td>
         </tr>
         @endforeach
      </tbody>   
   </table>
</div>
@endsection
