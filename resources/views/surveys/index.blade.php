<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Sondages de ') }} {{ $organization->name }}
            </h2>
            {{-- Bouton de création --}}
            @can('update', $organization)
                <a href="{{ route('surveys.create', $organization) }}"  class="w-full mt-4 p-6 bg-[#1b1b18] text-white py-2 rounded-lg transition"> + Créer un sondage
                </a>
            @endcan
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    @if(session('status'))
                        <div class="mb-4 font-medium text-sm text-green-600">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if($surveys->isEmpty())
                        <div class="text-center py-10 text-gray-500">
                            <p>Aucun sondage pour le moment.</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Titre</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dates</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Anonyme</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($surveys as $survey)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ $survey->title }}</div>
                                                <div class="text-sm text-gray-500">{{ Str::limit($survey->description, 50) }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                Du {{ \Carbon\Carbon::parse($survey->start_date)->format('d/m/Y') }}<br>
                                                Au {{ \Carbon\Carbon::parse($survey->end_date)->format('d/m/Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                @if($survey->is_anonymous)
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Oui</span>
                                                @else
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Non</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                                
                                                {{-- NOUVEAU BOUTON : LIEN PUBLIC --}}
                                                <a href="{{ route('surveys.public.show', $survey->token) }}" target="_blank" class="text-teal-600 hover:text-teal-900 bg-teal-50 px-3 py-1 rounded-md border border-teal-200">
                                                    🔗 Lien public
                                                </a>

                                                {{-- Bouton Questions --}}
                                                @can('update', $survey)
                                                    <a href="{{ route('surveys.questions.index', $survey) }}" class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 px-3 py-1 rounded-md border border-indigo-200">
                                                        Questions ({{ $survey->questions_count ?? 0 }})
                                                    </a>
                                                @endcan

                                                {{-- Bouton Modifier --}}
                                                @can('update', $survey)
                                                    <a href="{{ route('surveys.edit', $survey) }}" class="text-yellow-600 hover:text-yellow-900">Modifier</a>
                                                @endcan

                                                {{-- Bouton Supprimer --}}
                                                @can('delete', $survey)
                                                    <form action="{{ route('surveys.destroy', $survey) }}" method="POST" class="inline-block" onsubmit="return confirm('Êtes-vous sûr ?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-900 ml-2">Supprimer</button>
                                                    </form>
                                                @endcan


                                                @can('update', $survey)
                                                    <a href="{{ route('surveys.results', $survey) }}" class="text-purple-600 hover:text-purple-900 bg-purple-50 px-3 py-1 rounded-md border border-purple-200" title="Voir les statistiques">
                                                        📊 Résultats
                                                    </a>
                                                @endcan
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>