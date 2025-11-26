<?php

namespace App\DTOs;


use Illuminate\Http\Request;
use App\Models\Organization;
use App\Models\User;

final class SurveyDTO
{
      public function __construct(
        public readonly ?int $id,
        public readonly int $organizationId,
        public readonly int $userId,
        public readonly ?string $token,
        public readonly string $title,
        public readonly ?string $description,
        public readonly string $startDate,
        public readonly string $endDate,
        public readonly bool $isAnonymous,
        public readonly ?string $createdAt,
        public readonly ?string $updatedAt,
       ) {}


    public static function fromRequest(Request $request): self
    {
        $organization = $request->route('organization');
        if (!$organization instanceof Organization) {
             throw new \InvalidArgumentException("Organization not found in route for Survey creation.");
        }
        
        // Utilisation de input/boolean/user() pour extraire les données
        return new self(
            id: null,
            organizationId: $organization->id,
            userId: $request->user()->id,
            token: null, // Le token sera généré dans l'Action
            title: $request->input('title'),
            description: $request->input('description'),
            startDate: $request->input('start_date'),
            endDate: $request->input('end_date'),
            isAnonymous: $request->boolean('is_anonymous'),
            createdAt: null,
            updatedAt: null,
        );
    }
}