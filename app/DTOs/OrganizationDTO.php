<?php

namespace App\DTOs;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\Request;

final class OrganizationDTO
{
    public function __construct(
        public readonly ?string $name,
        public readonly ?User $actor,
        public readonly ?Organization $organization,
        public readonly ?User $member,
        public readonly ?string $memberEmail,
        public readonly ?string $memberRole,
        public readonly ?int $organizationId,
    ) {}

    public static function fromRequest(Request $request): self
    {
        $organization = $request->route('organization');
        $member = $request->route('user');
        $organizationId = $request->input('organization_id');

        if ($organizationId === null && $organization instanceof Organization) {
            $organizationId = $organization->id;
        }

        return new self(
            name: $request->input('name'),
            actor: $request->user(),
            organization: $organization instanceof Organization ? $organization : null,
            member: $member instanceof User ? $member : null,
            memberEmail: $request->input('email'),
            memberRole: $request->input('role'),
            organizationId: $organizationId !== null ? (int) $organizationId : null,
        );
    }
}
