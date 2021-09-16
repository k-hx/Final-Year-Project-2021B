@extends('layouts.app')

@section('content')
@if(Session::has('success'))
<div class="alert alert-success" role="alert">
   {{ Session::get('success')}}
</div>
@endif


<div>
   <table id="categoryOfSalaryComponentTable">
      <thead>
         <tr>
            <td>ID</td>
            <td>Category of Salary Component Name</td>
            <td>Salary Component</td>
         </tr>
      </thead>

      <tbody>
         @foreach($categoriesOfSalaryComponent as $category)
         <tr>
            <td>{{$category->id}}</td>
            <td>{{$category->name}}</td>
            <td>
               @foreach($salaryComponents as $salaryComponent)
                  @if($salaryComponent->category == $category->id)
                     {{ $salaryComponent->name }}
                     <br>
                  @endif
               @endforeach
            </td>
         </tr>
         @endforeach
      </tbody>
   </table>
</div>
@endsection

@section('script')
<script>
$(document).ready(function() {
   $('#categoryOfSalaryComponentTable').DataTable({
      "pagingType": "full_numbers",
   });
});
</script>
@endsection
