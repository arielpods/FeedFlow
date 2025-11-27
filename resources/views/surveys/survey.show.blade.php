@extends('layouts.app')

@section('content')
    <div class="container">

        <h1 class="mb-4">{{ $survey->title }}</h1>

        <p>{{ $survey->description }}</p>

        {{-- afficher les questions du sondage --}}
        <form action="#">
            @foreach ($survey->questions as $question)
                <div class="mb-3">
                    <label class="form-label">{{ $question->title }}</label>

                    {{-- Type radio --}}
                    @if ($question->question_type === 'radio')
                        @foreach ($question->data as $option)
                            <div>
                                <input type="radio" name="q{{ $question->id }}" value="{{ $option }}">
                                {{ $option }}
                            </div>
                        @endforeach
                    @endif

                    {{-- Type checkbox --}}
                    @if ($question->question_type === 'checkbox')
                        @foreach ($question->data as $option)
                            <div>
                                <input type="checkbox" name="q{{ $question->id }}[]" value="{{ $option }}">
                                {{ $option }}
                            </div>
                        @endforeach
                    @endif

                    {{-- Type text --}}
                    @if ($question->question_type === 'text')
                        <input type="text" class="form-control" name="q{{ $question->id }}">
                    @endif


                </div>
            @endforeach

            <button class="btn btn-primary">Envoyer</button>
        </form>

    </div>
@endsection
