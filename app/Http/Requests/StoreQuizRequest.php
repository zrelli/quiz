<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreQuizRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|min:10|max:255|unique:quizzes',
            'description' => 'nullable|string|min:10|max:500',
            'max_attempts' => 'integer|min:1|max:4',
            'duration' => 'integer|min:1|max:7',
            'test_type' => 'in:in_time,out_of_time',
            'started_at' => 'required|date',
            'is_public' => 'boolean'
        ];
    }
}
