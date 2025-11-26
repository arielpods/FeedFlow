<x-app-layout>
    @php
        $organizations = collect($organizations ?? []);
        $currentOrganization = $currentOrganization ?? $organizations->first();
        $availableRoles = $availableRoles ?? ['admin' => __('Administrateur'), 'member' => __('Membre')];
    @endphp

    <x-slot name="header">
        <div class="flex flex-col gap-1">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Story 1 - Gestion multi-organisations') }}
            </h2>
            <p class="text-sm text-gray-500">
                {{ __("Creez, modifiez ou supprimez vos organisations, invitez des membres et choisissez l'organisation active.") }}
            </p>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <div class="bg-white shadow sm:rounded-lg">
                <div class="p-6 space-y-6">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">
                            {{ __('Organisation active') }}
                        </h3>
                        <p class="mt-1 text-sm text-gray-500">
                            {{ __("Toutes vos actions (dashboards, projets...) se basent sur l'organisation que vous selectionnez ici.") }}
                        </p>
                        <form method="POST" action="{{ route('organizations.switch') }}" class="mt-4 flex flex-col gap-4 sm:flex-row sm:items-center">
                            @csrf
                            <div class="flex-1">
                                <x-input-label for="organization_id" :value="__('Choisir une organisation')" />
                                <select id="organization_id" name="organization_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @forelse($organizations as $organization)
                                        <option value="{{ $organization->id }}" @selected(optional($currentOrganization)->id === $organization->id)">
                                            {{ $organization->name }}
                                        </option>
                                    @empty
                                        <option value="">{{ __('Aucune organisation disponible') }}</option>
                                    @endforelse
                                </select>
                                <x-input-error :messages="$errors->get('organization_id')" class="mt-2" />
                            </div>

                            <div>
                                <x-primary-button @if($organizations->isEmpty()) disabled @endif>
                                    {{ __('Basculer') }}
                                </x-primary-button>
                            </div>
                        </form>
                    </div>

                    <div class="pt-6 border-t border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">
                            {{ __('Creer une nouvelle organisation') }}
                        </h3>
                        <p class="mt-1 text-sm text-gray-500">
                            {{ __("Separez vos activites (entreprise, ecole, clients...) en creant autant d'organisations que necessaire.") }}
                        </p>
                        <form method="POST" action="{{ route('organizations.store') }}" class="mt-4 space-y-4">
                            @csrf
                            <div>
                                <x-input-label for="organization-name" :value="__('Nom de l\'organisation')" />
                                <x-text-input id="organization-name" class="block w-full mt-1" type="text" name="name" :value="old('name')" required autofocus />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>
                            <div>
                                <x-primary-button>
                                    {{ __('Ajouter') }}
                                </x-primary-button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <h3 class="text-lg font-semibold text-gray-800">
                    {{ __('Vos organisations') }}
                </h3>

                @forelse($organizations as $organization)
                    <div class="bg-white shadow sm:rounded-lg">
                        <div class="p-6 space-y-6">
                            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                                <div>
                                    <h4 class="text-lg font-medium text-gray-900">{{ $organization->name }}</h4>
                                    <p class="text-sm text-gray-500">
                                        {{ trans_choice(':count membre|:count membres', collect(data_get($organization, 'members', []))->count(), ['count' => collect(data_get($organization, 'members', []))->count()]) }}
                                    </p>
                                </div>
                                @can('update', $organization)
                                    <div class="flex gap-3">
                                        <form method="POST" action="{{ route('organizations.destroy', $organization) }}">
                                            @csrf
                                            @method('DELETE')
                                            <x-danger-button>
                                                {{ __('Supprimer') }}
                                            </x-danger-button>
                                        </form>
                                    </div>
                                @endcan
                            </div>

                            @can('update', $organization)
                                <div class="border rounded-lg border-gray-200">
                                    <div class="p-4 border-b border-gray-200 bg-gray-50 rounded-t-lg">
                                        <h5 class="text-sm font-semibold uppercase tracking-wide text-gray-600">
                                            {{ __('Modifier les informations') }}
                                        </h5>
                                    </div>
                                    <div class="p-4">
                                        <form method="POST" action="{{ route('organizations.update', $organization) }}" class="space-y-4">
                                            @csrf
                                            @method('PATCH')
                                            <div>
                                                <x-input-label for="organization-name-{{ $organization->id }}" :value="__('Nom de l\'organisation')" />
                                                <x-text-input
                                                    id="organization-name-{{ $organization->id }}"
                                                    class="block w-full mt-1"
                                                    type="text"
                                                    name="name"
                                                    value="{{ old('name', $organization->name) }}"
                                                    required
                                                />
                                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                            </div>
                                            <div>
                                                <x-primary-button>
                                                    {{ __('Enregistrer') }}
                                                </x-primary-button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @endcan

                            <div class="border rounded-lg border-gray-200">
                                <div class="p-4 border-b border-gray-200 bg-gray-50 rounded-t-lg flex items-center justify-between">
                                    <h5 class="text-sm font-semibold uppercase tracking-wide text-gray-600">
                                        {{ __('Membres') }}
                                    </h5>
                                    @can('update', $organization)
                                        <span class="text-xs text-gray-500">{{ __('Invitez ou revoquez des membres') }}</span>
                                    @endcan
                                </div>

                                @php
                                    $members = collect(data_get($organization, 'members', []));
                                @endphp

                                <div class="p-4 space-y-4">
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <thead>
                                                <tr>
                                                    <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        {{ __('Membre') }}
                                                    </th>
                                                    <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        {{ __('Role') }}
                                                    </th>
                                                    <th scope="col" class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        {{ __('Actions') }}
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-gray-200">
                                                @forelse($members as $member)
                                                    @php
                                                        $fullName = trim(($member->first_name ?? '') . ' ' . ($member->last_name ?? ''));
                                                        $displayName = $fullName !== '' ? $fullName : ($member->name ?? $member->email);
                                                        $role = $member->pivot->role ?? __('Membre');
                                                    @endphp
                                                    <tr>
                                                        <td class="px-3 py-3">
                                                            <div class="text-sm font-medium text-gray-900">{{ $displayName }}</div>
                                                            <div class="text-sm text-gray-500">{{ $member->email }}</div>
                                                        </td>
                                                        <td class="px-3 py-3 text-sm text-gray-500 capitalize">
                                                            {{ $role }}
                                                        </td>
                                                        <td class="px-3 py-3 text-right text-sm font-medium">
                                                            @can('update', $organization)
                                                                <form method="POST" action="{{ route('organizations.members.destroy', [$organization, $member]) }}" class="inline">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="text-red-600 hover:text-red-900">
                                                                        {{ __('Retirer') }}
                                                                    </button>
                                                                </form>
                                                            @else
                                                                <span class="text-gray-400">-</span>
                                                            @endcan
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="3" class="px-3 py-4 text-center text-sm text-gray-500">
                                                            {{ __('Aucun membre pour le moment.') }}
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>

                                    @can('update', $organization)
                                        <div class="border-t border-gray-200 pt-4">
                                            <h6 class="text-sm font-medium text-gray-900">
                                                {{ __('Inviter un membre') }}
                                            </h6>
                                            <p class="text-sm text-gray-500">
                                                {{ __("Envoyez une invitation par e-mail et affectez un role immediatement.") }}
                                            </p>
                                            <form method="POST" action="{{ route('organizations.members.store', $organization) }}" class="mt-4 grid gap-4 sm:grid-cols-3">
                                                @csrf
                                                <div class="sm:col-span-2">
                                                    <x-input-label for="invite-email-{{ $organization->id }}" :value="__('Adresse e-mail')" />
                                                    <x-text-input
                                                        id="invite-email-{{ $organization->id }}"
                                                        class="block w-full mt-1"
                                                        type="email"
                                                        name="email"
                                                        :value="old('email')"
                                                        required
                                                    />
                                                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                                </div>
                                                <div>
                                                    <x-input-label for="invite-role-{{ $organization->id }}" :value="__('Role')" />
                                                    <select id="invite-role-{{ $organization->id }}" name="role" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                                        @foreach($availableRoles as $role => $label)
                                                            <option value="{{ $role }}">{{ $label }}</option>
                                                        @endforeach
                                                    </select>
                                                    <x-input-error :messages="$errors->get('role')" class="mt-2" />
                                                </div>
                                                <div class="sm:col-span-3">
                                                    <x-primary-button>
                                                        {{ __("Envoyer l'invitation") }}
                                                    </x-primary-button>
                                                </div>
                                            </form>
                                        </div>
                                    @endcan
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white shadow sm:rounded-lg">
                        <div class="p-6 text-center text-gray-500">
                            {{ __('Aucune organisation pour le moment. Commencez par en creer une ci-dessus.') }}
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
