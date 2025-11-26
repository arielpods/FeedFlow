<?php

namespace App\Policies;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class OrganizationPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Organization $organization): bool
    {
        return $this->isMember($user, $organization);
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Organization $organization): bool
    {
        return $this->isAdmin($user, $organization);
    }

    public function delete(User $user, Organization $organization): bool
    {
        return $this->isAdmin($user, $organization);
    }

    public function restore(User $user, Organization $organization): bool
    {
        return false;
    }

    public function forceDelete(User $user, Organization $organization): bool
    {
        return false;
    }

    protected function isMember(User $user, Organization $organization): bool
    {
        return $organization->members->contains($user);
    }

    protected function isAdmin(User $user, Organization $organization): bool
    {
        if ($organization->user_id === $user->id) {
            return true;
        }

        $member = $organization->members->firstWhere('id', $user->id);

        return $member && data_get($member->pivot, 'role') === 'admin';
    }
}
