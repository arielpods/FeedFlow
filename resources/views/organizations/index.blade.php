<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Vos Organisations') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white p-6 shadow sm:rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Créer une nouvelle organisation</h3>
                <form method="POST" action="{{ route('organizations.store') }}" class="flex gap-4">
                    @csrf
                    <div class="flex-1">
                        <input type="text" name="name" placeholder="Nom de l'organisation" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block w-full" required>
                    </div>
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shrink-0">
                        Créer
                    </button>
                </form>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Liste de vos organisations</h3>

                    <div class="grid gap-6">
                        @forelse ($organizations as $organization)
                            <a href="{{ route('survey.index', $organization) }}" class="block bg-gray-50 p-4 border border-gray-200 rounded-lg shadow-sm space-y-4 hover:bg-gray-100 transition duration-150 group">

                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        {{-- Ajout de group-hover:text-indigo-600 pour un effet visuel de lien --}}
                                        <div class="font-bold text-lg group-hover:text-indigo-600 transition">{{ $organization->name }}</div>
                                        <div class="text-sm text-gray-500">
                                            @if($organization->pivot)
                                                Rôle : {{ $organization->pivot->role === 'admin' ? 'Administrateur' : 'Membre' }}
                                            @endif
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-3" onclick="event.stopPropagation();">
                                        @if(isset($currentOrganization) && $currentOrganization->id !== $organization->id)
                                            <form method="POST" action="{{ route('organizations.switch') }}">
                                                @csrf
                                                <input type="hidden" name="organization_id" value="{{ $organization->id }}">
                                                <button type="submit" class="text-blue-600 hover:text-blue-900 text-sm font-medium">
                                                    Basculer
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-green-700 text-sm font-semibold px-3 py-1 bg-green-100 rounded-full">Actuelle</span>
                                        @endif

                                        @can('update', $organization)
                                            <button 
                                                class="text-yellow-600 hover:text-yellow-800 text-sm font-medium"
                                                onclick="document.getElementById('edit-{{ $organization->id }}').classList.toggle('hidden'); event.stopPropagation();"> {{-- event.stopPropagation() ajouté pour le bouton Modifier --}}
                                                Modifier
                                            </button>

                                            <form method="POST" action="{{ route('organizations.destroy', $organization) }}" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer {{ $organization->name }} ?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium">
                                                    Supprimer
                                                </button>
                                            </form>
                                            
                                            <form method="GET" action="{{ route('organizations.members.index', $organization->id) }}">
                                                <button type="submit" class="text-blue-600 hover:text-blue-900 text-sm font-medium">
                                                    Members
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </div>
                                
                                @can('update', $organization)
                                    <div id="edit-{{ $organization->id }}" class="hidden pt-4 border-t border-gray-200 mt-4">
                                        <h4 class="font-semibold mb-2">Modifier l'organisation :</h4>
                                        {{-- Ajout de onclick="event.stopPropagation();" au formulaire pour empêcher la navigation lors de la soumission --}}
                                        <form method="POST" action="{{ route('organizations.update', $organization) }}" class="flex items-end gap-4" onclick="event.stopPropagation();"> 
                                            @csrf
                                            @method('PATCH')
                                            
                                            {{-- CORRECTION: Utilisation d'une div flex-1 wrapper pour forcer l'input à coexister avec le bouton --}}
                                            <div class="flex-1">
                                                <input type="text" name="name" value="{{ $organization->name }}" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block w-full" required>
                                            </div>

                                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 transition ease-in-out duration-150 shrink-0">
                                                Sauvegarder
                                            </button>
                                        </form>
                                    </div>
                                @endcan
                            </a>
                        @empty
                            <div class="bg-white text-center py-8 border-2 border-dashed border-gray-300 rounded-lg">
                                <div class="text-gray-500 mb-2">
                                    {{ __('Aucune organisation pour le moment.') }}
                                </div>
                                <p class="text-sm text-gray-400">Utilisez le formulaire ci-dessus pour en créer une.</p>
                            </div>
                        @endforelse
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>
