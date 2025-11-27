<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Créer un sondage pour ') }} {{ $organization->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- OUVERTURE DU FORMULAIRE CORRECTE VERS LA ROUTE STORE --}}
                    <form method="POST" action="{{ route('survey.store', $organization) }}">
                        @csrf

                        {{-- Si vous avez vraiment un fichier _form valide, vous pouvez le garder : --}}
                        {{-- @include('surveys._form') --}}
                        
                        {{-- MAIS je vous conseille d'utiliser directement les champs ici pour être sûr : --}}
                        
                        <div class="mb-4">
                            <label for="title" class="block font-medium text-sm text-gray-700">Titre</label>
                            <input id="title" type="text" name="title" value="{{ old('title') }}" required autofocus class="border-gray-300 rounded-md shadow-sm mt-1 block w-full">
                            @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="mb-4">
                            <label for="description" class="block font-medium text-sm text-gray-700">Description</label>
                            <textarea id="description" name="description" class="border-gray-300 rounded-md shadow-sm mt-1 block w-full">{{ old('description') }}</textarea>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="start_date" class="block font-medium text-sm text-gray-700">Date de début</label>
                                <input id="start_date" type="date" name="start_date" value="{{ old('start_date', now()->format('Y-m-d')) }}" required class="border-gray-300 rounded-md shadow-sm mt-1 block w-full">
                            </div>
                            <div>
                                <label for="end_date" class="block font-medium text-sm text-gray-700">Date de fin</label>
                                <input id="end_date" type="date" name="end_date" value="{{ old('end_date') }}" required class="border-gray-300 rounded-md shadow-sm mt-1 block w-full">
                            </div>
                        </div>

                        <div class="mb-6 flex items-center">
                            <input id="is_anonymous" type="checkbox" name="is_anonymous" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                            <label for="is_anonymous" class="ml-2 block font-medium text-sm text-gray-700">Sondage Anonyme ?</label>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded-md hover:bg-gray-700 transition">
                                Valider
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>