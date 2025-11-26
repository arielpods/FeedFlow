<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Membres de l\'organisation') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white p-6 shadow sm:rounded-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">{{ $organization->name }}</h3>
                        <p class="text-sm text-gray-500">Liste des membres</p>
                    </div>

                    <div class="flex items-center gap-3">
                        <a href="{{ route('organizations.index') }}" class="text-blue-600 hover:text-blue-900 text-sm font-medium">Retour</a>
                    </div>
                </div>

                <div class="mt-6">
                    @if(session('status'))
                        <div class="text-sm text-green-600 mb-4">{{ session('status') }}</div>
                    @endif

                    <div class="grid gap-4">
                        @forelse($members as $member)
                            <div class="p-4 bg-gray-50 border border-gray-200 rounded-lg flex items-center justify-between">
                                <div>
                                    <div class="font-semibold">{{ $member->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $member->email }}</div>
                                </div>

                                <div class="flex items-center gap-4">
                                    <div class="text-sm text-gray-600">Rôle : <span class="font-medium">{{ data_get($member->pivot, 'role') === 'admin' ? 'Administrateur' : 'Membre' }}</span></div>

                                    @can('update', $organization)
                                        @if($member->id !== auth()->id())
                                            <form method="POST" action="{{ route('organizations.members.destroy', [$organization->id, $member->id]) }}" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce membre ?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium">Retirer</button>
                                            </form>
                                        @endif
                                    @endcan
                                </div>
                            </div>
                        @empty
                            <div class="p-6 bg-white border border-dashed border-gray-300 rounded-lg text-center text-gray-500">
                                Aucun membre pour le moment.
                            </div>
                        @endforelse
                    </div>
                </div>

                @can('update', $organization)
                    <div class="mt-6 border-t pt-6">
                        <h4 class="font-semibold mb-2">Inviter un membre</h4>
                        <form method="POST" action="{{ route('organizations.members.store', $organization->id) }}" class="flex gap-4 items-end">
                            @csrf
                            <div class="flex-1">
                                <input type="email" name="email" placeholder="Email du membre" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block w-full" required>
                            </div>
                            <div>
                                <select name="role" class="border-gray-300 rounded-md shadow-sm">
                                    <option value="member">Membre</option>
                                    <option value="admin">Administrateur</option>
                                </select>
                            </div>

                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Inviter
                            </button>
                        </form>
                    </div>
                @endcan

            </div>
        </div>
    </div>
</x-app-layout>
