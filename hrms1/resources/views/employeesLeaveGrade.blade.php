@extends('layouts.app')

<link rel="stylesheet" href="{{ asset('css/style2.css') }}">

@section('content')
@if(Session::has('success'))
    <div class="alert alert-success" role="alert">
        {{ Session::get('success')}}
    </div>
@endif

<div>
   <table>
      <tr>
         <td>ID</td>
         <td>Employee Name</td>
         <td>Leave Grade</td>
         <td>Action</td>
      </tr>

      @foreach($employees as $employee)
      <tr>
         <td>{{ $employee->id }}</td>
         <td>{{ $employee->full_name }}</td>
         <td>
         
         </td>
         <td>
            <a href="#" class="btn btn-warning" >Leave Grade</a>
            <a href="#" class="btn btn-danger" onclick="return confirm('Delete ?')">Delete</a>
         </td>
      </tr>
      @endforeach

   </table>
</div>


@endsection
