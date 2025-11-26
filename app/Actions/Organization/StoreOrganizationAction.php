<?php
namespace App\Actions\Organization;

use App\DTOs\OrganizationDTO;
use App\Models\Organization;
use Illuminate\Support\Facades\DB;

final class StoreOrganizationAction
{
    public function __construct() {}

    /**
     * Store an organization
     * @param OrganizationDTO $dto
     * @return array
     */
    public function handle(OrganizationDTO $dto): array
    {
        return DB::transaction(function () use ($dto) {
            $organization = Organization::create([
                'name' => $dto->name,
                'user_id' => optional($dto->actor)->id,
            ]);

            if ($dto->actor) {
                $organization->members()->syncWithoutDetaching([
                    $dto->actor->id => ['role' => 'admin'],
                ]);
            }

            return [
                'organization' => $organization->load('members'),
            ];
        });
    }
}
