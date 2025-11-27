@extends('layouts.app')

@section('content')
<h1>Sondages</h1>

<a href="{{ route('survey.create', $organization) }}" class="btn btn-primary mb-3">Créer un sondage</a>
<table class="table">
    <thead>
        <tr>
            <th>Titre</th>
            <th>Période</th>
            <th>Anonyme</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($surveys as $survey)
        <tr>
            <td>{{ $survey->title }}</td>
            <td>{{ $survey->start_date }} → {{ $survey->end_date }}</td>
            <td>{{ $survey->is_anonymous ? 'Oui' : 'Non' }}</td>
            <td>
                <a href="{{ route('surveys.edit', $survey) }}" class="btn btn-sm btn-warning">Modifier</a>

                @can('delete', $survey)
                <form action="{{ route('surveys.destroy', $survey) }}" method="POST" style="display:inline-block">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger">Supprimer</button>
                </form>
                @endcan
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
