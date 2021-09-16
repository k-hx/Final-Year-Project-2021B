@extends('layouts.app')
<script type="text/javascript">
@foreach($titleComponents as $titleComponent)
document.getElementById("amount").value=Math.abs({{ $titleComponent->amount }});
@endforeach
function changeSalaryComponent() {
   //get selected category of salary component
   var selectedCategory = document.getElementById("categoryOfSalaryComponent");
   var result = selectedCategory.options[selectedCategory.selectedIndex].value;

   var salaryComponentOptions = document.getElementsByClassName("salaryComponentOption");

   for(var i=0; i<salaryComponentOptions.length; i++) {
      if(salaryComponentOptions[i].classList.contains(result)) {
         salaryComponentOptions[i].hidden = false;
      } else {
         salaryComponentOptions[i].hidden = true;
      }
   }

}
</script>
@section('content')
<div style="text-align:center">
   <form method="post" action="{{ route('editSalaryComponentForJobTitle') }}" enctype="multipart/form-data">
      @csrf
      @foreach($titleComponents as $titleComponent)
      <p>
         <label for="id" class="label">ID</label>
         <input type="text" name="id" id="id" value="{{ $titleComponent->id }}" readonly>
      </p>

      <p>
         <label for="categoryOfSalaryComponent" class="label">Category</label>
         <select class="form-control" id="categoryOfSalaryComponent" name="categoryOfSalaryComponent" onchange="changeSalaryComponent()">
            @foreach($currentSalaryComponents as $currentSalaryComponent)
               @php
                  $current=$currentSalaryComponent->category;
               @endphp
            @endforeach

            @foreach($categoriesOfSalaryComponent as $category)
            <option value="{{ $category->id }}"
               @if($category->id == $current)
                  selected
               @endif>
               {{ $category->name }}
            </option>
            @endforeach
         </select>
      </p>

      <p>
         <label for="salaryComponent">Salary Component: </label>
         <select name= "salaryComponent" class="form-control" required>
            <option class="salaryComponentOption" value="{{ $titleComponent->salary_component }}">{{ $titleComponent->salaryComponentName }}</option>
            @foreach($categoriesOfSalaryComponent as $category)
               @foreach($salaryComponents as $salaryComponent)
                  @if($salaryComponent->category == $category->id)
                     @php $added = 0; @endphp
                        @foreach($currentTitleComponents as $currentTitleComponent)
                           @if($salaryComponent->id == $currentTitleComponent->salary_component)
                              @php $added = 1; @endphp
                           @endif
                        @endforeach

                        @if($added==0)
                        <option class="salaryComponentOption {{ $category->id }}" value="{{ $salaryComponent->id }}" hidden>{{ $salaryComponent->name }}</option>
                        @endif
                  @endif
               @endforeach
            @endforeach
         </select>

         <br>
      </p>

      <p>
         <label for="amount">Amount (RM):</label>
         <input type="number" name="amount" min="0" id="amount" value="" required>
      </p>

      @endforeach
      <p>
         <input type="submit" name="update" value="Update">
      </p>
   </form>
</div>
@endsection
