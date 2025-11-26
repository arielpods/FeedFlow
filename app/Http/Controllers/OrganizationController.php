<?php

namespace App\Http\Controllers;

use App\Actions\Organization\DeleteOrganizationAction;
use App\Actions\Organization\StoreOrganizationAction;
use App\Actions\Organization\UpdateOrganizationAction;
use App\DTOs\OrganizationDTO;
use App\Http\Requests\Organization\DeleteOrganization;
use App\Http\Requests\Organization\StoreOrganization;
use App\Http\Requests\Organization\UpdateOrganization;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class OrganizationController extends Controller
{
    public function __construct(
        private readonly StoreOrganizationAction $storeOrganization,
        private readonly UpdateOrganizationAction $updateOrganization,
        private readonly DeleteOrganizationAction $deleteOrganization,
    ) {
    }

    public function index(Request $request): View
    {
        $user = $request->user();
        $organizations = $user->organizations()->with('members')->get();
        $currentOrganizationId = session('current_organization_id');
        $currentOrganization = $organizations->firstWhere('id', $currentOrganizationId) ?? $organizations->first();

        return view('organizations.index', [
            'organizations' => $organizations,
            'currentOrganization' => $currentOrganization,
            'availableRoles' => [
                'admin' => 'Administrateur',
                'member' => 'Membre',
            ],
        ]);
    }

    public function store(StoreOrganization $request): RedirectResponse
    {
        $dto = OrganizationDTO::fromRequest($request);
        $this->storeOrganization->handle($dto);

        return Redirect::route('organizations.index')->with('status', 'organization-created');
    }

    public function update(UpdateOrganization $request, Organization $organization): RedirectResponse
    {
        $dto = OrganizationDTO::fromRequest($request);
        $this->updateOrganization->handle($dto);

        return Redirect::route('organizations.index')->with('status', 'organization-updated');
    }

    public function destroy(DeleteOrganization $request, Organization $organization): RedirectResponse
    {
        $dto = OrganizationDTO::fromRequest($request);
        $this->deleteOrganization->handle($dto);

        return Redirect::route('organizations.index')->with('status', 'organization-deleted');
    }

    public function inviteMember(Request $request, Organization $organization): RedirectResponse
    {
        $this->authorize('update', $organization);

        $data = $request->validate([
            'email' => ['required', 'email'],
            'role' => ['required', 'in:admin,member'],
        ]);

        $user = User::where('email', $data['email'])->first();
        if ($user) {
            $organization->members()->syncWithoutDetaching([
                $user->id => ['role' => $data['role']],
            ]);
        } else {
            
        }

        return Redirect::route('organizations.members.index', $organization->id)->with('status', 'member-invited');
    }

    public function removeMember(Request $request, Organization $organization, User $user): RedirectResponse
    {
        $this->authorize('update', $organization);

        $organization->members()->detach($user->id);

        return Redirect::route('organizations.members.index', $organization->id)->with('status', 'member-removed');
    }

    public function members(Request $request, Organization $organization): View
    {
        $this->authorize('view', $organization);

        $members = $organization->members()->get();

        return view('organizations.members.index', [
            'organization' => $organization,
            'members' => $members,
        ]);
    }

    public function switch(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'organization_id' => ['required', 'integer', 'exists:organizations,id'],
        ]);

        $organization = $request->user()->organizations()->findOrFail($validated['organization_id']);

        $request->session()->put('current_organization_id', $organization->id);

        return Redirect::back()->with('status', 'organization-switched');
    }
}
