@extends('layouts.app')

<link rel="stylesheet" href="{{ asset('css/style2.css') }}">

@section('content')

@if(Session::has('success'))
    <div class="alert alert-success" role="alert">
        {{ Session::get('success')}}
    </div>
@endif

@foreach($leaveGrades as $leaveGrade)
<a href="{{ route('leaveEntitlement', ['id' => $leaveGrade->id]) }}" class="btn btn-info">Back</a>

<div style="text-align:center">

   @foreach($leaveEntitlements as $leaveEntitlement)
   <form action="{{ route('updateLeaveEntitlement', ['leaveGradeId'=>$leaveGrade->id,'id' => $leaveEntitlement->id]) }}" method="post">
      @csrf
      <p>
         <label for="leavesEntitled">Leave Type: </label>
         <select name= "leaveType" id= "leaveType" class="form-control" required>
            <option name= "leaveType" value="{{ $leaveEntitlement->leaveType}}">{{ $leaveEntitlement->leaveTypeName }}</option>

            @foreach($leaveTypes as $leaveType)
               @php $added = 0; @endphp
               @foreach($currentEntitlements as $currentEntitlement)
                  @if($leaveType->id==$currentEntitlement->leaveTypeId)
                     @php $added = 1; @endphp
                  @endif
               @endforeach

               @if($added==0)
               <option name= "leaveType" value="{{ $leaveType->id }}">{{ $leaveType->name }}</option>
               @endif
            @endforeach
         </select>

         <br>

         <label for="num_of_days">Number of days entitled:</label>
         <input type="number" name="num_of_days" min="0" value="{{ $leaveEntitlement->num_of_days }}" required>
      </p>

      <input type="submit" name="updateLeaveEntitlement" value="Update" class="btn btn-primary">
   </form>
   @endforeach
@endforeach
</div>
@endsection
