<?php

namespace App\Http\Requests\Survey;

use App\Models\Survey;
use Illuminate\Foundation\Http\FormRequest;

class StoreSurveyAnswerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */

    public function authorize(): bool
    {
        return true; // Autoriser tout le monde (public)
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */

    public function rules(): array
    {
        $token = $this->route('token');
        // On récupère le sondage et ses questions pour construire les règles
        $survey = Survey::where('token', $token)->with('questions')->firstOrFail();

        $rules = [];

        foreach ($survey->questions as $question) {
            // Le nom du champ HTML est "answers[ID_QUESTION]"
            $fieldName = 'answers.' . $question->id;

            $rules[$fieldName] = match ($question->question_type) {
                'text' => ['required', 'string', 'max:1000'],
                'radio' => ['required', 'string'],
                'checkbox' => ['required', 'array'],
                default => ['nullable'],
            };
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'answers.*.required' => 'Cette question nécessite une réponse.',
        ];
    }
}