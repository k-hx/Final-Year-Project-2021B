@extends('layouts.app')
@section('content')

<div>
@foreach($employeeLeaves as $employeeLeave)
<p>Employee leave ID: {{ $employeeLeave->id }}</p>
<p>Employee ID: {{ $employeeLeave->employee }}</p>
<p>Leave type ID: {{ $employeeLeave->leave_type }}</p>
@endforeach

<p>Number: {{ $number }}</p>
</div>
@endsection
