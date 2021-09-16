@extends('layouts.app')
@section('content')

<div>
@foreach($titleComponents as $titleComponent)
<p>{{ $titleComponent->id }}</p>
@endforeach
</div>
@endsection
