<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Vos Organisations') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            {{-- Section de création (Exemple simple) --}}
            <div class="bg-white p-4 shadow sm:rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Créer une nouvelle organisation</h3>
                <form method="POST" action="{{ route('organizations.store') }}" class="flex gap-4">
                    @csrf
                    <input type="text" name="name" placeholder="Nom de l'organisation" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block w-full" required>
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Créer
                    </button>
                </form>
            </div>

            {{-- Liste des organisations --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Liste de vos organisations</h3>
                    
                    <div class="grid gap-4">
                        @forelse ($organizations as $organization)
                            <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                                <div>
                                    <div class="font-bold text-lg">{{ $organization->name }}</div>
                                    <div class="text-sm text-gray-500">
                                        {{-- Affichage du rôle si disponible via la relation pivot --}}
                                        @if($organization->pivot)
                                            Rôle : {{ $organization->pivot->role === 'admin' ? 'Administrateur' : 'Membre' }}
                                        @endif
                                    </div>
                                </div>

                                <div class="flex items-center gap-2">
                                    {{-- Bouton pour basculer sur cette organisation --}}
                                    @if(isset($currentOrganization) && $currentOrganization->id !== $organization->id)
                                        <form method="POST" action="{{ route('organizations.switch') }}">
                                            @csrf
                                            <input type="hidden" name="organization_id" value="{{ $organization->id }}">
                                            <button type="submit" class="text-blue-600 hover:underline text-sm">
                                                Basculer
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-green-600 text-sm font-bold px-2 py-1 bg-green-100 rounded">Actuelle</span>
                                    @endif
                                </div>
                            </div>
                        @empty
                            {{-- C'est ici que le bug se trouvait avant : le bloc @empty gère l'absence d'organisations --}}
                            <div class="bg-white text-center py-8">
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
