@extends('layouts.app')

@section('content')
@if(Session::has('success'))
<div class="alert alert-success" role="alert">
   {{ Session::get('success')}}
</div>
@endif

<td><a href="{{ route('showCreateSalaryComponent') }}" class="btn btn-primary">Create SalaryComponent</a></td>

<div>
   <table id="salaryComponentTable">
      <thead>
         <tr>
            <td>ID</td>
            <td>Salary Component Name</td>
            <td>Category</td>
            <td>Action</td>
         </tr>
      </thead>

      <tbody>
         @foreach($salaryComponents as $salaryComponent)
         <tr>
            <td>{{$salaryComponent->id}}</td>
            <td>{{$salaryComponent->name}}</td>
            <td>{{$salaryComponent->categoryName}}</td>
            <td>
               <a href="{{ route('editSalaryComponent',['id' => $salaryComponent->id]) }}" class="btn btn-warning" >Edit</a>
               <a href="{{ route('deleteSalaryComponent',['id' => $salaryComponent->id]) }}" class="btn btn-danger" onclick="return confirm('Delete {{$salaryComponent->name}}?')">Delete</a></td>
         </tr>
         @endforeach
      </tbody>
   </table>
</div>
@endsection

@section('script')
<script>
$(document).ready(function() {
   $('#salaryComponentTable').DataTable({
      "pagingType": "full_numbers",
   });
});
</script>
@endsection
