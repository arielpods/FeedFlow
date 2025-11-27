<?php
namespace App\Actions\Survey;

use App\DTOs\SurveyQuestionDTO;
use App\Models\SurveyQuestion;
use Illuminate\Support\Facades\DB;

final class StoreSurveyQuestionAction
{
    public function __construct() {}

    /**
     * Store a question for a survey.
     */
    public function handle(SurveyQuestionDTO $dto): array
    {
        return DB::transaction(function () use ($dto) {
            $question = SurveyQuestion::create([
                'survey_id' => $dto->surveyId,
                'title' => $dto->title,
                'question_type' => $dto->questionType,
                'options' => $dto->options ?? [],
            ]);

            return ['question' => $question];
        });
    }
}
