<?php

namespace App\DTOs;

use Illuminate\Http\Request;
use App\Models\Organization;
use App\Models\Survey; // <-- Import nécessaire
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
        // 1. Essayer de récupérer l'organisation (Cas CRÉATION)
        $organization = $request->route('organization');
        
        // 2. Essayer de récupérer le sondage (Cas MODIFICATION)
        $survey = $request->route('survey');

        $organizationId = null;
        $surveyId = null;

        // Logique de détection
        if ($organization instanceof Organization) {
            // Cas Création : on a l'organisation dans l'URL
            $organizationId = $organization->id;
        } elseif ($survey instanceof Survey) {
            // Cas Modification : on a le sondage, on récupère son organization_id
            $organizationId = $survey->organization_id;
            $surveyId = $survey->id;
        } else {
            // Cas d'erreur
            throw new \InvalidArgumentException("Impossible de trouver le contexte (Organisation ou Sondage) pour ce DTO.");
        }
        
        return new self(
            id: $surveyId,
            organizationId: $organizationId,
            userId: $request->user()->id,
            token: null, 
            title: $request->input('title'),
            description: $request->input('description'),
            startDate: $request->input('start_date'),
            endDate: $request->input('end_date'),
            isAnonymous: $request->boolean('is_anonymous'), // Utilisation de boolean() pour gérer le checkbox
            createdAt: null,
            updatedAt: null,
        );
    }
}