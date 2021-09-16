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

   @foreach($employees as $employee)
      <table id="employeePayrollTable">
      <thead>
         <tr>
            <th>Salary Component ID</th>
            <th>Salary Component Name</th>
            <th>Amount</th>
            <th>Action</th>
         </tr>
      </thead>

      <tbody>
         @foreach($employeePayrolls as $employeePayroll)
         <tr>
            <td>{{ $employeePayroll->component}}</td>
            <td>{{ $employeePayroll->salaryComponentName }}</td>
            <td>{{ $employeePayroll->amount }}</td>
            <td>
               <a href="{{ route('showEditEmployeePayroll', ['id' => $employeePayroll->id]) }}" class="btn btn-warning" >Edit</a>
               <a href="{{ route('deleteEmployeePayroll', ['id' => $employeePayroll->id]) }}" class="btn btn-danger" onclick="return confirm('Delete {{$employeePayroll->salaryComponentName}}?')">Delete</a>
            </td>
         </tr>
         @endforeach
      </tbody>
   </table>

   <br>
   <br>

   <p>Add salary component to current payroll</p>
   <form action="{{ route('addEmployeePayroll') }}" method="post">
      @csrf
      <input type="hidden" name="id" value="{{ $employee->id }}">

      <p>
         <label for="categoryOfSalaryComponent">Select from category:</label>
         <select class="form-control" id="categoryOfSalaryComponent" name="categoryOfSalaryComponent" onchange="changeSalaryComponent();">
            <option value="default">-- Select category --</option>
            @foreach($categoriesOfSalaryComponent as $category)
            <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
         </select>
      </p>

      <p>
         <label for="salaryComponent">Salary Component: </label>
         <select name= "salaryComponent" class="form-control" required>
            <option value="default">-- Select salary component --</option>
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
         <input type="number" name="amount" min="0" value="" required>
      </p>

      <input type="submit" name="addEmployeePayroll" value="Add Salary Component" class="btn btn-info">
   </form>
   @endforeach
</div>
@endsection
