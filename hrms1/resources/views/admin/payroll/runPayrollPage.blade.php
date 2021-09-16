@extends('layouts.app')

@section('content')
<h2>Payroll for Current Month</h2>

<div class="text-align:center">
   <table id="salaryStructureForAllJobTitleTable">
      <thead>
         <tr>
            <td>Employee ID</td>
            <td>Employee Name</td>
            <td>Job Title</td>
            <td>Action</td>
         </tr>
      </thead>

      <tbody>
         @foreach($employees as $employee)
         <tr>
            <td>{{$employee->id}}</td>
            <td>{{$employee->full_name}}</td>
            <td>{{$employee->jobTitleName}}</td>
            <td>
               <a href="{{ route('showEmployeePayrollPage',['id' => $employee->id]) }}" class="btn btn-warning">Manage Payroll Item</a>
            </td>
         </tr>
         @endforeach
      </tbody>
   </table>
</div>
@endsection
