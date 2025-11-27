<?php

namespace App\Http\Requests\Survey;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSurveyQuestionRequest extends FormRequest
{
    public function authorize(): bool
    {
        $survey = $this->route('survey');
        return $survey && $this->user()->can('update', $survey);
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'question_type' => ['required', 'string', Rule::in(['text', 'radio', 'checkbox'])], // Scale retiré
            
            // Validation du tableau d'options
            'options' => [
                'nullable', 
                'array', 
                'min:2', // Au moins 2 choix c'est mieux pour un QCM
                Rule::requiredIf(fn () => in_array($this->question_type, ['radio', 'checkbox']))
            ],
            
            // Validation de chaque option à l'intérieur du tableau
            'options.*' => ['required', 'string', 'max:255', 'distinct'],
        ];
    }

    public function messages()
    {
        return [
            'options.required_if' => 'Vous devez ajouter des options pour ce type de question.',
            'options.min' => 'Veuillez proposer au moins 2 options.',
            'options.*.required' => 'Une option ne peut pas être vide.',
            'options.*.distinct' => 'Les options ne doivent pas être identiques.',
        ];
    }
}