<?php

namespace App\Http\Controllers;

use App\Actions\Survey\StoreSurveyAction;
use App\Actions\Survey\UpdateSurveyAction;
use App\DTOs\SurveyDTO;
use App\Http\Requests\Survey\DeleteSurveyRequest; // <-- Import Important
use App\Http\Requests\Survey\StoreSurveyRequest;
use App\Http\Requests\Survey\UpdateSurveyRequest;
use App\Models\Organization;
use App\Models\Survey;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Redirect;

class SurveyController extends Controller
{
    public function __construct(
        private readonly StoreSurveyAction $storeSurvey,
        private readonly UpdateSurveyAction $updateSurvey
    ) {}

    public function survey(Organization $organization): View
    {
        $this->authorize('view', $organization);
        // On récupère les sondages
        $surveys = $organization->surveys()->get();

        return view('surveys.index', [
            'organization' => $organization,
            'surveys' => $surveys,
        ]);
    }

    public function create(Organization $organization): View
    {
        // Seuls les membres avec droits peuvent créer (souvent admin, ou via policy update orga)
        $this->authorize('update', $organization);
        return view('surveys.create', ['organization' => $organization]);
    }

    public function store(StoreSurveyRequest $request, Organization $organization)
    {
        $dto = SurveyDTO::fromRequest($request);
        $this->storeSurvey->handle($dto);
        return Redirect::route('survey.index', $organization)->with('status', 'survey-created');
    }

    public function edit(Survey $survey): View
    {
        // Vérification via la Policy Survey
        $this->authorize('update', $survey);

        return view('surveys.edit', [
            'survey' => $survey,
            'organization' => $survey->organization
        ]);
    }

    public function update(UpdateSurveyRequest $request, Survey $survey): RedirectResponse
    {
        // L'autorisation est faite dans UpdateSurveyRequest
        
        // Mise à jour (Idéalement via une Action, mais direct ici pour faire simple selon vos fichiers)
        $survey->update($request->validated());

        return Redirect::route('survey.index', $survey->organization_id)->with('status', 'survey-updated');
    }

    // --- C'EST ICI LA SUPPRESSION ---
    public function destroy(DeleteSurveyRequest $request, Survey $survey): RedirectResponse
    {
        // L'autorisation est faite dans DeleteSurveyRequest

        $organizationId = $survey->organization_id;
        $survey->delete();

        return Redirect::route('survey.index', $organizationId)->with('status', 'survey-deleted');
    }
}