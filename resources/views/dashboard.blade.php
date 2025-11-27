<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tableau de bord') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="text-2xl font-bold mb-2">Bonjour, {{ Auth::user()->first_name }} ! 👋</h3>
                    <p class="text-gray-600">Bienvenue sur votre espace FeedFlow. Voici un aperçu de vos activités.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-indigo-500 hover:shadow-md transition">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-indigo-100 p-3 rounded-full">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                        <span class="text-2xl font-bold text-gray-800">{{ Auth::user()->organizations()->count() }}</span>
                    </div>
                    <h4 class="text-lg font-semibold text-gray-800 mb-1">Organisations</h4>
                    <p class="text-sm text-gray-500 mb-4">Gérez vos équipes et vos espaces de travail.</p>
                    <a href="{{ route('organizations.index') }}" class="text-indigo-600 hover:text-indigo-800 font-medium text-sm inline-flex items-center">
                        Voir mes organisations <span class="ml-1">&rarr;</span>
                    </a>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-teal-500 hover:shadow-md transition">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-teal-100 p-3 rounded-full">
                            <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                            </svg>
                        </div>
                        {{-- Vous pouvez injecter le nombre total de sondages ici via le contrôleur si besoin --}}
                        <span class="text-2xl font-bold text-gray-800">-</span>
                    </div>
                    <h4 class="text-lg font-semibold text-gray-800 mb-1">Sondages Actifs</h4>
                    <p class="text-sm text-gray-500 mb-4">Créez et diffusez vos questionnaires.</p>
                    {{-- Redirige vers la liste des organisations pour choisir où créer le sondage --}}
                    <a href="{{ route('organizations.index') }}" class="text-teal-600 hover:text-teal-800 font-medium text-sm inline-flex items-center">
                        Accéder aux sondages <span class="ml-1">&rarr;</span>
                    </a>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-gray-500 hover:shadow-md transition">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-gray-100 p-3 rounded-full">
                            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <h4 class="text-lg font-semibold text-gray-800 mb-1">Mon Compte</h4>
                    <p class="text-sm text-gray-500 mb-4">Gérez vos informations personnelles.</p>
                    <a href="{{ route('profile.edit') }}" class="text-gray-600 hover:text-gray-800 font-medium text-sm inline-flex items-center">
                        Modifier mon profil <span class="ml-1">&rarr;</span>
                    </a>
                </div>

            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Dernières Activités</h3>
                    <div class="border-t border-gray-100">
                        <ul class="divide-y divide-gray-100">
                            {{-- Exemple d'item vide si aucune activité --}}
                            @if(Auth::user()->organizations->isEmpty())
                                <li class="py-4 text-center text-gray-500 italic">
                                    Vous n'avez pas encore rejoint d'organisation.
                                    <a href="{{ route('organizations.index') }}" class="text-indigo-600 hover:underline">Commencez ici !</a>
                                </li>
                            @else
                                {{-- Liste des organisations récentes --}}
                                @foreach(Auth::user()->organizations->take(3) as $org)
                                    <li class="py-4 flex justify-between items-center hover:bg-gray-50 transition px-2 rounded">
                                        <div class="flex items-center gap-3">
                                            <div class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-xs">
                                                {{ substr($org->name, 0, 2) }}
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">{{ $org->name }}</p>
                                                <p class="text-xs text-gray-500">Membre depuis {{ $org->pivot->created_at->format('d/m/Y') }}</p>
                                            </div>
                                        </div>
                                        <a href="{{ route('organizations.index') }}" class="text-xs text-gray-400 hover:text-indigo-600">Voir</a>
                                    </li>
                                @endforeach
                            @endif
                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>