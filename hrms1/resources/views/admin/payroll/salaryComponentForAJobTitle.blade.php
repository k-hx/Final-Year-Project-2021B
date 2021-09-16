@extends('layouts.app')

<script type="text/javascript">
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

@if(Session::has('success'))
<div class="alert alert-success" role="alert">
   {{ Session::get('success')}}
</div>
@endif

<div style="text-align:center">

   @foreach($jobTitles as $jobTitle)
   <h2>Job Title: {{ $jobTitle->job_title_name }}</h2>

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
                  <a href="{{ route('editTitleComponent', ['jobTitleId' => $jobTitle->id,'id' => $currentTitleComponent->id]) }}" class="btn btn-warning" >Edit</a>
                  <a href="{{ route('deleteTitleComponent', ['jobTitleId' => $jobTitle->id,'id' => $currentTitleComponent->id]) }}" class="btn btn-danger" onclick="return confirm('Delete {{$currentTitleComponent->salaryComponentName}}?')">Delete</a>
            </td>
            </tr>
            @endforeach
         </tbody>
      </table>

   <br>
   <br>

   <form action="" method="post">
      @csrf
      <input type="hidden" name="id" value="{{ $jobTitle->id }}">
      <p>
         <label for="categoryOfSalaryComponent">Select from category</label>
         <select class="form-control" id="categoryOfSalaryComponent" name="categoryOfSalaryComponent" onchange="changeSalaryComponent();">
            <option value="default">-- Select category --</option>
            @foreach($categoriesOfSalaryComponent as $category)
            <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
         </select>
      </p>

      <p>
         <label for="salaryComponent">Salary Component: </label>
         <select name= "salaryComponent" class="form-control">
            <option value="default">-- Select salary component --</option>
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

         <label for="amount">Amount (RM):</label>
         <input type="number" name="amount" min="0" value="" required>
      </p>

      <input type="submit" name="add" value="Add Salary Component" class="btn btn-primary">
   </form>
   @endforeach
</div>

@endsection

@section('script')
<script>
$(document).ready(function() {
   $('#salaryStructureForAJobTitleTable').DataTable({
      "pagingType": "full_numbers",
   });
});
</script>
@endsection
