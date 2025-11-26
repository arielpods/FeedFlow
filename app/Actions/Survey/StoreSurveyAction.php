<?php
namespace App\Actions\Survey;

use App\DTOs\SurveyDTO;
use App\Models\Survey;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

final class StoreSurveyAction
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
            $survey = Survey::create([
                'organization_id' => $dto->organizationId,
                'user_id' => $dto->userId,
                'title' => $dto->title,
                'description' => $dto->description,
                'start_date' => $dto->startDate,
                'end_date' => $dto->endDate,
                'is_anonymous' => $dto->isAnonymous,
                'token' => Str::random(32),
            ]);

            return [
                'survey' => $survey,
            ];
        });
    }
}