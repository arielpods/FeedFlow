<?php
namespace App\Actions\Survey;

use App\DTOs\SurveyQuestionDTO;
use Illuminate\Support\Facades\DB;
use App\Models\SurveyQuestion;

final class StoreSurveyQuestionAction
{
    public function __construct() {}

    /**
     * Store a Survey
     * @param SurveyQuestionDTO $dto
     * @return array
     */
    public function execute(SurveyQuestionDTO $dto): SurveyQuestion
    {
        // Création de la question via Eloquent
        $question = SurveyQuestion::create([
               'survey_id' => 1,
               'title'         => $dto->title,
               'question_type' => $dto->questionType,
               'options'          => $dto->options,
           ]);
        return $question;
    }
}
