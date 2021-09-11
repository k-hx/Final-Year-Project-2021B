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
   if({{ $employeeLeaveGrade }}==leaveGradeId) {
      number=1;
   }
   @endforeach

   var leaveGradeName="";
   @foreach($leaveGrades as $leaveGrade)
   if({{ $leaveGrade->id }}==leaveGradeId) {
      leaveGradeName={{ $leaveGrade->name }};
   }
   @endforeach
   
}

</script>

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
            <a href="{{ route('leaveEntitlement', ['id' => $leaveGrade->id]) }}" class="btn btn-info" >Leave Entitlement</a>
            <a href="{{ route('deleteLeaveGrade', ['id' => $leaveGrade->id]) }}" class="btn btn-danger" onclick="validate({{ $leaveGrade->id }})" onclick="">Delete</a>
         </td>
      </tr>
      @endforeach

   </table>
</div>
@endsection
