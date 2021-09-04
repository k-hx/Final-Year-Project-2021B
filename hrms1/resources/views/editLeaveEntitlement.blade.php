@extends('layouts.app')
@section('content')
<div style="text-align:center">
   @foreach($leaveGrades as $leaveGrade)
   <p>{{ $leaveGrade->name }}</p>

      @foreach($currentEntitlements as $currentEntitlement)
      <p>{{ $currentEntitlement->name}}</p>
      <p>{{  }}</p>
      @endforeach

   @endforeach
</div>
@endsection
