<?php

namespace App\Http\Requests\Survey;

use Illuminate\Foundation\Http\FormRequest;

class DeleteSurveyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $survey = $this->route('survey');
        // On utilise la SurveyPolicy définie ci-dessus
        return $survey && $this->user()->can('delete', $survey);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            // Pas de règles de validation spécifiques pour une suppression,
            // l'autorisation suffit.
        ];
    }
}