<?php

namespace App\DTOs;

use Illuminate\Http\Request;

final class SurveyQuestionDTO
{
    public function __construct(
        public readonly int $surveyId,
        public readonly string $title,
        public readonly string $questionType,
        public readonly ?array $options,
    ) {}

    public static function fromRequest(Request $request): self
    {
        $survey = $request->route('survey');

        // Récupération directe du tableau (array)
        $options = $request->input('options');

        // Si le type est 'text', on force options à null pour être propre
        if ($request->input('question_type') === 'text') {
            $options = null;
        }

        return new self(
            surveyId: $survey->id,
            title: $request->input('title'),
            questionType: $request->input('question_type'),
            options: $options, // Plus besoin de explode() car c'est déjà un array
        );
    }
}