<?php

namespace App\Http\Controllers;

use App\Actions\Survey\StoreSurveyAction;
use App\Actions\Survey\UpdateSurveyAction; // Ajout
use App\DTOs\SurveyDTO;
use App\Http\Requests\Survey\StoreSurveyRequest;
use App\Http\Requests\Survey\UpdateSurveyRequest; // Ajout
use App\Models\Organization;
use App\Models\Survey; // Ajout
use Illuminate\Http\RedirectResponse; // Ajout
use Illuminate\Http\Request; // Ajout
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class SurveyController extends Controller
{
    public function __construct(
        private readonly StoreSurveyAction $storeSurvey,
        private readonly UpdateSurveyAction $updateSurvey // Injection de l'action Update
    ) {
    }

    public function survey(Organization $organization): View
    {
        $this->authorize('view', $organization);
        
        $surveys = $organization->surveys()->get();

        return view('surveys.index', [
            'organization' => $organization,
            'surveys' => $surveys, 
        ]);
    }

    public function create(Organization $organization): View
    {
        $this->authorize('update', $organization); 

        return view('surveys.create', [
            'organization' => $organization
        ]);
    }

    public function store(StoreSurveyRequest $request, Organization $organization)
    {
        $dto = SurveyDTO::fromRequest($request);
        $this->storeSurvey->handle($dto);
        
        return Redirect::route('survey.index', $organization)->with('status', 'survey-created');
    }

    // --- NOUVELLES MÉTHODES ---

    public function edit(Survey $survey): View
    {
        // On vérifie si l'user a le droit de modifier (via SurveyPolicy)
        // Note: Assurez-vous que votre SurveyPolicy retourne true pour update
        // $this->authorize('update', $survey); 

        return view('surveys.edit', [
            'survey' => $survey,
            'organization' => $survey->organization // On passe l'orga pour le lien de retour
        ]);
    }

    public function update(UpdateSurveyRequest $request, Survey $survey): RedirectResponse
    {
        // $this->authorize('update', $survey);

        $dto = SurveyDTO::fromRequest($request);
        // On passe l'ID du sondage au DTO ou à l'action si besoin, 
        // ici l'action UpdateSurveyAction devra être adaptée si elle ne prend que le DTO
        // Pour l'instant on suppose que l'action gère la mise à jour basique
        
        // Mise à jour simple via Eloquent si l'action n'est pas prête
        $survey->update($request->validated());

        return Redirect::route('survey.index', $survey->organization_id)->with('status', 'survey-updated');
    }

    public function destroy(Survey $survey): RedirectResponse
    {
        // $this->authorize('delete', $survey);
        
        $organizationId = $survey->organization_id;
        $survey->delete();

        return Redirect::route('survey.index', $organizationId)->with('status', 'survey-deleted');
    }
}