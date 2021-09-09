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
      if ({{ $employee->leaveGrade }}=={{ $employee->leaveGradeId }}) {
            number=1;
            break;
      }
      @endforeach
   }

   var leaveGradeName = "";

   if (number==0) {
      @foreach($leaveGrades as $leaveGrade)
      if({{ $leaveGrade->id }}==leaveGradeId) {
         leaveGradeName={{ $leaveGrade->name }}
      }
      @endforeach
      
      return confirm('Delete {{$leaveGrade->name}}?');
   } else {
      return alert('The leave grade is still asigned to at least an employee!');
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
