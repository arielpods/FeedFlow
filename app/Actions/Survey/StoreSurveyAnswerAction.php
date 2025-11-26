<?php
namespace App\Actions\Survey;

use App\DTOs\SurveyDTO;
use Illuminate\Support\Facades\DB;
use App\Models\SurveyAnswer;

final class StoreSurveyAnswerAction
{
    public function __construct() {}

    /**
     * Store a Survey
     * @param SurveyDTO $dto
     * @return array
     */
    public function handle(SurveyDTO $dto): array
    {
        return DB::transaction(function () use ($dto) {
        });
    }
}
