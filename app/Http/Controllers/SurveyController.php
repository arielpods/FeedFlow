<?php

namespace App\Http\Controllers;

use App\Actions\Survey\StoreSurveyAction;
use App\DTOs\SurveyDTO;
use App\Http\Requests\Survey\StoreSurveyRequest;
use App\Models\Organization;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class SurveyController extends Controller
{
    public function __construct(
        private readonly StoreSurveyAction $storeSurvey 
    ) {
    }

    public function survey(Organization $organization): View
    {
       
        $this->authorize('view', $organization);

        return view('surveys.index', [
            'organization' => $organization 
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
    
}

