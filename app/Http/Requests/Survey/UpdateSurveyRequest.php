<?php

namespace App\Http\Requests\Survey;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSurveyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // 1. On récupère le sondage concerné depuis la route
        $survey = $this->route('survey');

        // 2. On vérifie que le sondage existe et que l'utilisateur peut modifier l'organisation liée
        // (Utilise la même Policy que pour les organisations)
        return $survey && $this->user()->can('update', $survey->organization);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'start_date'  => ['required', 'date'],
            'end_date'    => ['required', 'date', 'after:start_date'],
            'is_anonymous'=> ['sometimes', 'boolean'],
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        // Astuce pour les checkboxes :
        // Si la case est décochée, le navigateur n'envoie rien.
        // $this->has('is_anonymous') renverra false, ce qui mettra la valeur à 0 (faux).
        // Si elle est cochée, cela renverra true (1).
        $this->merge([
            'is_anonymous' => $this->has('is_anonymous'),
        ]);
    }
}