@extends('layouts.app')
@section('content')

<div style="text-align:center">

   @foreach($leaveGrades as $leaveGrade)
   <h2>Leave Grade: {{ $leaveGrade->name }}</h2>
   @endforeach

   <form action="" method="post">
      @foreach
      <input type="submit" name="create" value="Update">
   </form>
</div>
@endsection
