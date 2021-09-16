@extends('layouts.app')

@section('content')
<div style="text-align:center">
   <form method="post" action="{{ route('updateSalaryComponent') }}" enctype="multipart/form-data">
      @csrf
      @foreach($salaryComponents as $salaryComponent)
      <p>
         <label for="id" class="label">ID</label>
         <input type="text" name="id" id="id" value="{{ $salaryComponent->id}}" readonly>
      </p>
      <p>
         <label for="name" class="label">Salary Component Name</label>
         <input type="text" name="name" id="name" value="{{ $salaryComponent->name}}">
      </p>

      <p>
         <label for="categoryOfSalaryComponent" class="label">Category</label>
         <select class="form-control" id="categoryOfSalaryComponent" name="categoryOfSalaryComponent">
            @foreach($categoriesOfSalaryComponent as $category)
               <option value="{{ $category->id }}"
                  @if($category->id == $salaryComponent->category)
                  selected
                  @endif>
                  {{ $category->name }}
               </option>
            @endforeach
         </select>
      </p>
      @endforeach

      <p>
         <input type="submit" name="update" value="Update">
      </p>
   </form>
</div>
@endsection
