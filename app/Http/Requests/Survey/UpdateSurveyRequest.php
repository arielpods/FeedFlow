<?php

namespace App\Http\Requests\Survey;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSurveyRequest extends FormRequest
{
    public function authorize(): bool
    {
        $survey = $this->route('survey');
        // la on utilise la SurveyPolicy (update) avec can normalement si j'ai bien compris
        return $survey && $this->user()->can('update', $survey);
    }

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

    protected function prepareForValidation()
    {
        $this->merge([
            'is_anonymous' => $this->has('is_anonymous'),
        ]);
    }
}