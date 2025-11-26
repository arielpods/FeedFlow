@extends('layouts.app')

@section('content')
<h1>Modifier le sondage</h1>

<form action="{{ route('surveys.update', $survey) }}" method="POST">
    @method('PUT')
    @include('surveys._form')
</form>
@endsection
