<?php
namespace App\Actions\Organization;

use App\DTOs\OrganizationDTO;
use App\Models\Organization;
use Illuminate\Support\Facades\DB;

final class DeleteOrganizationAction
{
    public function __construct() {}

    /**
     * Delete an organization
     * @param OrganizationDTO $dto
     * @return array
     */
    public function handle(OrganizationDTO $dto): array
    {
        return DB::transaction(function () use ($dto) {
            $organization = $dto->organization;

            if (!$organization && $dto->organizationId) {
                $organization = Organization::findOrFail($dto->organizationId);
            }

            if (!$organization) {
                throw new \InvalidArgumentException('Organization not provided.');
            }

            $organization->members()->detach();
            $organization->delete();

            return [
                'deleted' => true,
            ];
        });
    }
}
