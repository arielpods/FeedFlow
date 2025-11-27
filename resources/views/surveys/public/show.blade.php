<x-guest-layout>
    <div class="max-w-3xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-t-4 border-indigo-600">
            
            <h1 class="text-3xl font-bold mb-2 text-gray-900">{{ $survey->title }}</h1>
            <p class="text-gray-600 mb-8 text-lg">{{ $survey->description }}</p>

            <form method="POST" action="{{ route('surveys.public.submit', $survey->token) }}">
                @csrf

                <div class="space-y-8">
                    @foreach($survey->questions as $question)
                        <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                            <label class="block font-semibold text-lg text-gray-800 mb-3">
                                {{ $question->title }}
                            </label>

                            {{-- Texte Libre --}}
                            @if($question->question_type === 'text')
                                <textarea name="answers[{{ $question->id }}]" rows="3" class="w-full border-gray-300 rounded-md shadow-sm" required>{{ old('answers.'.$question->id) }}</textarea>
                            
                            {{-- Radio --}}
                            @elseif($question->question_type === 'radio')
                                <div class="space-y-3">
                                    @foreach($question->options as $option)
                                        <div class="flex items-center">
                                            <input type="radio" id="q{{$question->id}}_{{Str::slug($option)}}" name="answers[{{ $question->id }}]" value="{{ $option }}" class="text-indigo-600 focus:ring-indigo-500 h-4 w-4" required>
                                            <label for="q{{$question->id}}_{{Str::slug($option)}}" class="ml-3 text-gray-700 cursor-pointer">{{ $option }}</label>
                                        </div>
                                    @endforeach
                                </div>

                            {{-- Checkbox --}}
                            @elseif($question->question_type === 'checkbox')
                                <div class="space-y-3">
                                    @foreach($question->options as $option)
                                        <div class="flex items-center">
                                            <input type="checkbox" id="q{{$question->id}}_{{Str::slug($option)}}" name="answers[{{ $question->id }}][]" value="{{ $option }}" class="text-indigo-600 focus:ring-indigo-500 h-4 w-4 rounded">
                                            <label for="q{{$question->id}}_{{Str::slug($option)}}" class="ml-3 text-gray-700 cursor-pointer">{{ $option }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            @error('answers.'.$question->id)
                                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    @endforeach
                </div>

                <div class="mt-8 flex justify-end">
                    <button type="submit" class="bg-indigo-600 text-white font-bold py-3 px-8 rounded-md hover:bg-indigo-500 transition">
                        Envoyer
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>