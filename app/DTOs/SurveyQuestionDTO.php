<?php

namespace App\DTOs;

use App\Http\Requests\Survey\StoreSurveyQuestionRequest;
use Illuminate\Http\Request;

final class SurveyQuestionDTO
{
    //définit les données nécessaires
    private function __construct(
        public readonly int $survey_id,
        public readonly string $title,
        public readonly string $question_type,
        public readonly array $options
    ) {}

    //  créer le DTO à partir d'une requête validée.
    public static function fromRequest(StoreSurveyQuestionRequest $request): self
    {

        return new self(
            survey_id: $request->survey_id,
            title: $request->title,
            question_type: $request->question_type,
            options: $request->options,
        );
    }
}
