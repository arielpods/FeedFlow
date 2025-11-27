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
}
