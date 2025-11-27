<?php

namespace App\DTOs;

use Illuminate\Http\Request;

final class SurveyAnswerDTO
{
    public function __construct(
        public readonly string $surveyToken,
        public readonly array $answers, 
        public readonly ?int $userId,
        public readonly ?string $ipAddress,
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            surveyToken: $request->route('token'),
            answers: $request->input('answers', []),
            userId: $request->user()?->id, // Null si l'utilisateur n'est pas connecté
            ipAddress: $request->ip(),
        );
    }
}