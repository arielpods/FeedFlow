<?php

namespace App\DTOs;

use Illuminate\Http\Request;

final class SurveyQuestionDTO
{
    //définit les données nécessaires
    private function __construct(
        public readonly int $surveyId,
        public readonly string $title,
        public readonly string $questionType,
        public readonly array $options
    ) {}

    //  créer le DTO à partir d'une requête validée.
    public static function fromRequest(Request $request): self
    {
        $options = $request->input('options', []);

        return new self(
            surveyId: $request->input('survey_id'),
            title: $request->input('title'),
            questionType: $request->input('question_type'),
            options: $request->input('options', []),
        );
    }
}
