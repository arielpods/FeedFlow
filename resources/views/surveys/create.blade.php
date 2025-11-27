<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Nouveau sondage') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl border border-gray-100">
                
                <div class="bg-indigo-600 p-6 sm:p-10">
                    <h3 class="text-2xl font-bold text-white mb-2">Configuration du sondage</h3>
                    <p class="text-indigo-100">Définissez les informations générales pour <span class="font-semibold text-white">{{ $organization->name }}</span>.</p>
                </div>

                <div class="p-6 sm:p-10">
                    <form method="POST" action="{{ route('surveys.store', $organization) }}" class="space-y-6">
                        @csrf

                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Titre du sondage</label>
                            <input id="title" type="text" name="title" value="{{ old('title') }}" required autofocus placeholder="Ex: Satisfaction employé 2024"
                                class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition">
                            @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description (Optionnel)</label>
                            <textarea id="description" name="description" rows="3" placeholder="Quel est l'objectif de ce sondage ?"
                                class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition">{{ old('description') }}</textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Date de début</label>
                                <input id="start_date" type="date" name="start_date" value="{{ old('start_date', now()->format('Y-m-d')) }}" required 
                                    class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition">
                            </div>
                            
                            <div>
                                <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">Date de fin</label>
                                <input id="end_date" type="date" name="end_date" value="{{ old('end_date') }}" required 
                                    class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition">
                            </div>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200 flex items-center justify-between">
                            <div>
                                <label for="is_anonymous" class="block text-sm font-medium text-gray-900">Réponses anonymes</label>
                                <p class="text-xs text-gray-500">Si activé, l'identité des répondants ne sera pas enregistrée.</p>
                            </div>
                            <div class="flex items-center h-5">
                                <input id="is_anonymous" type="checkbox" name="is_anonymous" value="1" class="h-5 w-5 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded cursor-pointer transition">
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-100">
                            <a href="{{ route('survey.index', $organization) }}" class="text-sm text-gray-600 hover:text-gray-900 font-medium px-4 py-2 transition">
                                Annuler
                            </a>
                            <button type="submit"  class="w-full mt-4 p-6 bg-[#1b1b18] text-white py-2 rounded-lg transition">
                                Créer le sondage
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>