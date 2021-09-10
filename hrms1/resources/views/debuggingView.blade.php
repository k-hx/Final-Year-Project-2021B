@extends('layouts.app')
@section('content')

<div>
   <h2>Leave Entitlement</h2>
@foreach($leaveEntitlements as $leaveEntitlement)
<p>Leave entitlement ID: {{ $leaveEntitlement->id }}</p>
<p>Leave grade: {{ $leaveEntitlement->leaveGrade }}</p>
<p>Leave grade: {{ $leaveEntitlement->leaveType }}</p>
<br>
@endforeach

<h2>Employee Leave</h2>
@foreach($employeeLeaves as $employeeLeave)
<p>Employee leave: {{ $employeeLeave->employee }}</p>
<p>Leave type: {{ $employeeLeave->leave_type }}</p>
<p>Total days: {{ $employeeLeave->total_days }}</p>
<br>
@endforeach
</div>
@endsection
