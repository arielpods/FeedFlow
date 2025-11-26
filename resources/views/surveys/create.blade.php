@extends('layouts.app')

@section('content')
<h1>Créer un sondage</h1>

<form action="{{ route('surveys.store') }}" method="POST">
    @include('surveys._form')
</form>
@endsection
