@extends('layouts.app')
@section('content')

@if(Session::has('success'))
<div class="alert alert-success" role="alert">
   {{ Session::get('success')}}
</div>
@endif

<div style="text-align:center">

   @foreach($jobTitles as $jobTitle)
   <h2>Leave Grade: {{ $jobTitle->name }}</h2>

      <table id="salaryStructureForAJobTitleTable">
         <thead>
            <tr>
               <th>Salary Component ID</th>
               <th>Salary Component Name</th>
               <th>Amount</th>
               <th>Action</th>
            </tr>
         </thead>

         <tbody>
            @foreach($currentTitleComponents as $currentTitleComponent)
            <tr>
               <td>{{ $currentTitleComponent->salary_component }}</td>
               <td>{{ $currentTitleComponent->salaryComponentName }}</td>
               <td>{{ $currentTitleComponent->amount }}</td>
               <td>
                  <a href="{{ route('editLeaveEntitlement', ['leaveGradeId' => $leaveGrade->id,'id' => $currentEntitlement->id]) }}" class="btn btn-warning" >Edit</a>
                  <a href="{{ route('deleteLeaveEntitlement', ['leaveGradeId' => $leaveGrade->id,'id' => $currentEntitlement->id]) }}" class="btn btn-danger" onclick="return confirm('Delete {{$currentEntitlement->leaveTypeName}}?')">Delete</a>
            </td>
            </tr>
            @endforeach
         </tbody>
      </table>

   <br>
   <br>

   <form action="{{ route('addLeaveEntitlement', ['id' => $leaveGrade->id]) }}" method="post">
      @csrf
      <input type="hidden" name="id" value="{{ $leaveGrade->id }}">
      <p>
         <label for="leavesEntitled">Leave Type: </label>
         <select name= "leaveType" id= "leaveType" class="form-control" required>
            @foreach($leaveTypes as $leaveType)

               @php $added = 0; @endphp
               @foreach($currentEntitlements as $currentEntitlement)
                  @if($leaveType->id==$currentEntitlement->leaveTypeId)
                     @php $added = 1; @endphp
                  @endif
               @endforeach

               @if($added==0 && $leaveType->status!='Deleted')
               <option value="{{ $leaveType->id }}">{{ $leaveType->name }}</option>
               @endif

            @endforeach
         </select>

         <br>

         <label for="num_of_days">Number of days entitled:</label>
         <input type="number" name="num_of_days" min="0" value="" required>
      </p>

      <input type="submit" name="addLeaveType" value="Add leave type" class="btn btn-info">
   </form>
   @endforeach
</div>
@endsection
