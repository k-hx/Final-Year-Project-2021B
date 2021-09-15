@extends('layouts.app')

@section('content')
@if(Session::has('success'))
    <div class="alert alert-success" role="alert">
        {{ Session::get('success')}}
    </div>
@endif

@if(Session::has('primary'))
    <div class="alert alert-primary" role="alert">
        {{ Session::get('primary')}}
    </div>
@endif

@if(Session::has('danger'))
    <div class="alert alert-danger" role="alert">
        {{ Session::get('danger')}}
    </div>
@endif

<a href="{{ route('createLeaveRecord') }}" class="btn btn-primary">Refresh</a>

<div>
   <table id="allAdminsLeaveGradeTable">
      <tr>
         <th>ID</th>
         <th>Admin Name</th>
         <th>Leave Grade</th>
         <th>Action</th>
      </tr>

      @foreach($admins as $admin)
      <tr>
         <td>{{ $admin->id }}</td>
         <td>{{ $admin->full_name }}</td>
         <td>{{ $admin->leave_grade }} {{ $admin->leaveGradeName }}</td>
         <td>
            <a href="{{ route('adminsLeaveGrade', ['id' => $admin->id]) }}" class="btn btn-primary">View/Edit Leave Information</a>
         </td>
      </tr>
      @endforeach

   </table>

</div>


@endsection
