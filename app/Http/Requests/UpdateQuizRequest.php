<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Validation\Rule;
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
        $quizId = basename(request()->path());
        $titleRule = [
            'required',
            'string',
            'min:10',
            'max:255',
            Rule::unique('quizzes')->ignore($quizId),
        ];
        return [
            'title' => $titleRule,
            'description' => 'nullable|string|min:10|max:500',
            // 'is_published' => 'boolean',
            'is_public' => 'boolean',
            'test_type' => 'in:in_time,out_of_time',
            'max_attempts' => 'integer|min:1|max:4',
            'duration' => 'integer|min:1|max:7',
            'started_at' => 'date',
        ];
    }
}
