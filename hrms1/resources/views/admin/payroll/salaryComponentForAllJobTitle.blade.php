@extends('layouts.app')

@section('content')
@if(Session::has('success'))
<div class="alert alert-success" role="alert">
   {{ Session::get('success')}}
</div>
@endif


<div>
   <table id="salaryStructureForAllJobTitleTable">
      <thead>
         <tr>
            <td>ID</td>
            <td>Job Title</td>
            <td>Salary Component</td>
            <td>Action</td>
         </tr>
      </thead>

      <tbody>
         @foreach($jobTitles as $jobTitle)
         <tr>
            <td>{{$jobTitle->id}}</td>
            <td>{{$jobTitle->job_title_name}}</td>
            <td>
               @foreach($titleComponents as $titleComponent)
                  @if($titleComponent->job_title == $jobTitle->id)
                     {{ $titleComponent->salaryComponentName }}
                     <br>
                  @endif
               @endforeach
            </td>
            <td>
               <a href="{{ route('showSalaryComponentForAJobTitle',['id' => $jobTitle->id]) }}" class="btn btn-primary">View</a>
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
   $('#salaryStructureForAllJobTitleTable').DataTable({
      "pagingType": "full_numbers",
   });
});
</script>
@endsection
