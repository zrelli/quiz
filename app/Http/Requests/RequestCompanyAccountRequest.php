<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Rules\UniqueCompanyEmail;
use App\Rules\UniqueCompanyTenant;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;

class RequestCompanyAccountRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required', 'string', 'max:255',
            ],
            'subdomain' => ['required', 'string', 'max:255', new UniqueCompanyTenant()],
            'email' => [
                'required', 'string', 'lowercase', 'email', 'max:255',
                new UniqueCompanyEmail()
            ],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ];
    }
}
