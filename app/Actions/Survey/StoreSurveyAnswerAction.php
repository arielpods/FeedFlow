<?php
namespace App\Actions\Survey;

use App\DTOs\SurveyAnswerDTO;
use App\Events\SurveyAnswerSubmitted;
use App\Models\Survey;
use App\Models\SurveyAnswer;
use Illuminate\Support\Facades\DB;

final class StoreSurveyAnswerAction
{
    public function handle(SurveyAnswerDTO $dto): void
    {
        DB::transaction(function () use ($dto) {
            $survey = Survey::where('token', $dto->surveyToken)->firstOrFail();

            foreach ($dto->answers as $questionId => $value) {
                // Si c'est un tableau (checkbox), on le stocke en JSON
                $finalValue = is_array($value) ? json_encode($value) : $value;

                SurveyAnswer::create([
                    'survey_id' => $survey->id,
                    'survey_question_id' => $questionId,
                    'user_id' => $dto->userId,
                    'answer' => $finalValue,
                ]);
            }

            // Déclencher l'événement (Critère d'acceptation 2)
            event(new SurveyAnswerSubmitted($survey));
        });
    }
}