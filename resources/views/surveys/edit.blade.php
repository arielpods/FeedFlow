<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Modifier le sondage : ') }} {{ $survey->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- Action vers la route UPDATE --}}
                    <form method="POST" action="{{ route('surveys.update', $survey) }}">
                        @csrf
                        @method('PATCH') {{-- <-- CORRECTION CRUCIALE ICI (PUT -> PATCH) --}}

                        {{-- Champs du formulaire (remplace votre @include pour être sûr que ça marche) --}}
                        
                        <div class="mb-4">
                            <label for="title" class="block font-medium text-sm text-gray-700">Titre</label>
                            <input id="title" type="text" name="title" value="{{ old('title', $survey->title) }}" required class="border-gray-300 rounded-md shadow-sm mt-1 block w-full">
                            @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="mb-4">
                            <label for="description" class="block font-medium text-sm text-gray-700">Description</label>
                            <textarea id="description" name="description" class="border-gray-300 rounded-md shadow-sm mt-1 block w-full">{{ old('description', $survey->description) }}</textarea>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="start_date" class="block font-medium text-sm text-gray-700">Date de début</label>
                                <input id="start_date" type="date" name="start_date" value="{{ old('start_date', $survey->start_date) }}" required class="border-gray-300 rounded-md shadow-sm mt-1 block w-full">
                            </div>
                            <div>
                                <label for="end_date" class="block font-medium text-sm text-gray-700">Date de fin</label>
                                <input id="end_date" type="date" name="end_date" value="{{ old('end_date', $survey->end_date) }}" required class="border-gray-300 rounded-md shadow-sm mt-1 block w-full">
                            </div>
                        </div>

                        <div class="mb-6 flex items-center">
                            <input id="is_anonymous" type="checkbox" name="is_anonymous" value="1" {{ old('is_anonymous', $survey->is_anonymous) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                            <label for="is_anonymous" class="ml-2 block font-medium text-sm text-gray-700">Sondage Anonyme ?</label>
                        </div>

                        <div class="flex justify-end gap-3">
                            {{-- Lien Annuler qui retourne à la liste --}}
                            <a href="{{ route('survey.index', $organization) }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 transition">
                                Annuler
                            </a>

                            <button type="submit" class="w-full mt-4 p-6 bg-[#1b1b18] text-white py-2 rounded-lg transition">
                                Mettre à jour
                            </button>
                            
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>