@extends('layouts.app')
@section('content')
<div style="text-align:center">
   <form method="post" action="{{ route('') }}" enctype="multipart/form-data">
      @csrf
      <p>
         <label for="name" class="label">Salary Component Name</label>
         <input type="text" name="name" id="name">
      </p>

      <p>
         <label for="category" class="label">Category</label>
         <select name="category" id="category">
            <option value=""></option>
         </select>
      </p>

      <p>
         <input type="submit" name="create" value="Create">
      </p>
   </form>
</div>
@endsection
