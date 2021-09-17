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

<form class="" action="{{ route('editEmployeePayroll') }}" method="post">
   @csrf
   @foreach($employeePayrolls as $employeePayroll)
   <input type="hidden" name="id" value="{{ $employeePayroll->id }}">
   <p>Employee ID:</p>
   <p>{{$employeePayroll->employeeName}}</p>

   <p>
      <label for="categoryOfSalaryComponent">Select from category:</label>
      <select class="form-control" id="categoryOfSalaryComponent" name="categoryOfSalaryComponent" onchange="changeSalaryComponent();">
         <option value="default">-- Select salary component --</option>

         @foreach($categoriesOfSalaryComponent as $category)
         <option value="{{ $category->id }}"
            @if($category->id == $currentCategory)
               selected
            @endif
            >{{ $category->name }}</option>
         @endforeach
      </select>
   </p>

   <p>
      <label for="salaryComponent">Salary Component: </label>
      <select name= "salaryComponent" class="form-control" required>
         <option class="salaryComponentOption" value="default">{{$currentComponent}}</option>
         @foreach($categoriesOfSalaryComponent as $category)
            @foreach($salaryComponents as $salaryComponent)
               @if($salaryComponent->category == $category->id)
                  @php $added = 0; @endphp
                     @foreach($employeePayrolls as $employeePayroll)
                        @if($salaryComponent->id == $employeePayroll->component)
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
      <input type="number" name="amount" min="0" value="{{ $employeePayroll->amount }}" required>
   </p>

   <p>
      <input type="submit" name="update" value="Update">
   </p>
   @endforeach
</form>
