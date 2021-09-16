
@foreach($employeePayrolls as $employeePayroll)
<input type="hidden" name="id" value="{{ $employeePayroll->id }}">
<p>Employee ID:</p>
<p>{{$employeePayroll->employeeName}}</p>

<p>
   <label for="categoryOfSalaryComponent">Select from category:</label>
   <select class="form-control" id="categoryOfSalaryComponent" name="categoryOfSalaryComponent" onchange="changeSalaryComponent();">
      <option value="default">-- Select salary component --</option>

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
@endforeach
