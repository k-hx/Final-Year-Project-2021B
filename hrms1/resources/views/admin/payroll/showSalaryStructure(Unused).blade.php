@extends('layouts.app')

@section('content')
@if(Session::has('success'))
<div class="alert alert-success" role="alert">
   {{ Session::get('success')}}
</div>
@endif


<div>
   <table id="salaryStructureTable">
      <thead>
         <tr>
            <td>ID</td>
            <td>Salary Structure Name</td>
            <td>Salary Component</td>
         </tr>
      </thead>

      <tbody>
         @foreach($salaryStructures as $salaryStructure)
         <tr>
            <td>{{$salaryStructure->id}}</td>
            <td>{{$salaryStructure->name}}</td>
            <td>
               @foreach($structureComponents as $structureComponent)
                  @if($structureComponent->salary_structure == $salaryStructure->id)
                     {{ $structureComponent->salaryComponentName }}
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
   $('#salaryStructureTable').DataTable({
      "pagingType": "full_numbers",
   });
});
</script>
@endsection
