<?php

namespace App\Http\Controllers;

use App\Actions\Survey\StoreSurveyAction;
use App\Actions\Survey\UpdateSurveyAction;
use App\DTOs\SurveyDTO;
use App\Http\Requests\Survey\DeleteSurveyRequest; 
use App\Http\Requests\Survey\StoreSurveyRequest;
use App\Http\Requests\Survey\UpdateSurveyRequest;
use App\Models\Organization;
use App\Models\Survey;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Redirect;
use App\Models\SurveyQuestion;
use App\Actions\Survey\StoreSurveyQuestionAction; 
use App\Http\Requests\Survey\StoreSurveyQuestionRequest; 
use App\DTOs\SurveyQuestionDTO; 
use App\Http\Requests\Survey\StoreSurveyAnswerRequest;
use App\DTOs\SurveyAnswerDTO;
use App\Actions\Survey\StoreSurveyAnswerAction;
use Illuminate\Support\Facades\DB;

class SurveyController extends Controller
{
    public function __construct(
        private readonly StoreSurveyAction $storeSurvey,
        private readonly UpdateSurveyAction $updateSurvey,
        private readonly StoreSurveyQuestionAction $storeQuestionAction,
        private readonly StoreSurveyAnswerAction $storeAnswerAction
    ) {}

    public function survey(Organization $organization): View
    {
        $this->authorize('view', $organization);
        // On récupère les sondages
        $surveys = $organization->surveys()->withCount('questions')->get();

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

    // --- GESTION DES QUESTIONS ---

    public function manageQuestions(Survey $survey): View
    {
        $this->authorize('update', $survey);

        return view('surveys.questions', [
            'survey' => $survey,
            'questions' => $survey->questions()->get() // Relation à définir dans le modèle Survey si pas fait
        ]);
    }

    public function storeQuestion(StoreSurveyQuestionRequest $request, Survey $survey)
    {
        // L'autorisation est faite dans la Request

        $dto = SurveyQuestionDTO::fromRequest($request);
        $this->storeQuestionAction->handle($dto);

        return Redirect::route('surveys.questions.index', $survey)->with('status', 'question-added');
    }

    public function destroyQuestion(SurveyQuestion $question)
    {
        // Vérifier que l'user a le droit sur le survey parent
        $this->authorize('update', $question->survey);
        
        $question->delete();
        
        return back()->with('status', 'question-deleted');
    }




    // --- PARTIE PUBLIQUE : RÉPONDRE AU SONDAGE ---

    public function showPublic(string $token): View
    {
        $survey = Survey::where('token', $token)->with('questions')->firstOrFail();

        // Vérification basique des dates
        if (now()->lt($survey->start_date) || now()->gt($survey->end_date)) {
            abort(404, 'Ce sondage n\'est pas disponible (dates invalides).');
        }

        return view('surveys.public.show', ['survey' => $survey]);
    }

    public function submitPublic(StoreSurveyAnswerRequest $request, string $token): RedirectResponse
    {
        $dto = SurveyAnswerDTO::fromRequest($request);
        
        $this->storeAnswerAction->handle($dto);

        return Redirect::route('surveys.public.thanks');
    }






    public function results(Survey $survey): View
    {
        // Seul le proprio/admin peut voir les résultats
        $this->authorize('update', $survey);

        // Charger les questions et leurs réponses
        $survey->load(['questions.answers', 'answers']);

        // --- GRAPHIQUE 1 : Évolution des participations (Timeline) ---
        // On compte les réponses à la première question comme indicateur de participation
        $firstQuestionId = $survey->questions->first()?->id;
        
        $datesData = [];
        if ($firstQuestionId) {
            $datesData = $survey->answers()
                ->where('survey_question_id', $firstQuestionId)
                ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
                ->groupBy('date')
                ->orderBy('date')
                ->get();
        }

        // --- GRAPHIQUE 2 : Répartition pour une question "Choix" (Radio/Checkbox) ---
        // On prend la première question de type radio ou checkbox pour l'exemple
        $chartQuestion = $survey->questions->whereIn('question_type', ['radio', 'checkbox'])->first();
        $distributionData = [];
        
        if ($chartQuestion) {
            // Initialiser les compteurs à 0 pour chaque option
            if ($chartQuestion->options) {
                foreach ($chartQuestion->options as $opt) {
                    $distributionData[$opt] = 0;
                }
            }

            // Compter les réponses
            foreach ($chartQuestion->answers as $ans) {
                if ($chartQuestion->question_type === 'checkbox') {
                    // Les checkbox sont stockées en JSON string, on décode
                    $choices = json_decode($ans->answer, true);
                    if (is_array($choices)) {
                        foreach ($choices as $choice) {
                            if (isset($distributionData[$choice])) {
                                $distributionData[$choice]++;
                            }
                        }
                    }
                } else {
                    // Radio : string simple
                    if (isset($distributionData[$ans->answer])) {
                        $distributionData[$ans->answer]++;
                    }
                }
            }
        }

        return view('surveys.results', [
            'survey' => $survey,
            'datesData' => $datesData,         // Pour le Graphique 1
            'chartQuestion' => $chartQuestion, // Pour le Graphique 2 (Question)
            'distributionData' => $distributionData, // Pour le Graphique 2 (Données)
        ]);
    }

    
}
