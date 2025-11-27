<?php

namespace App\Http\Requests\Survey;

use Illuminate\Foundation\Http\FormRequest;

class StoreSurveyQuestionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'survey_id' => ['required', 'exists:surveys,id'],
            'title' => ['required', 'string'],
            'question_type' => ['required', 'string', 'in:radio,checkbox,text'],
            'options' => ['nullable', 'array'],
        ];
    }

    public function messages(): array
    {
        return [
            'options.array' => 'Options must be an array.',
            'options.required' => 'Options are required.',
            'options.min' => 'Options must contain at least one option.',
            'options.max' => 'Options must contain at most 5 options.',
            'options.string' => 'Options must be strings.',
            'options.unique' => 'Options must be unique.',
            'options.in' => 'Options must be radio, checkbox, or text.',
            'question_type.in' => 'Question type must be radio, checkbox, or text.',
            'question_type.required' => 'Question type is required.',
            'title.required' => 'Title is required.',
            'survey_id.required' => 'Survey is required.',
            'survey_id.exists' => 'Survey does not exist.',
        ];
    }
}
