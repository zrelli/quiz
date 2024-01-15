<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
class UpdateQuizRequest extends FormRequest
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
            // 'max_attempts' => 'integer|min:1|max:4',
            // 'duration' => 'integer|min:1|max:2',
            // 'test_type' => 'in:in_time,out_of_time',
            // 'validity_duration' => 'required_if:test_type,out_of_time|integer|min:1|max:20',//for out of time type
            // 'started_at' => 'required|date',
        ];
    }
}
