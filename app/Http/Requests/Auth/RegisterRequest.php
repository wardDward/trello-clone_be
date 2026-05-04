<?php

namespace App\Http\Requests\Auth;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'user_name' => ['required', 'string', 'max:255', Rule::unique('users', 'user_name')],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')],
            'password' => ['required', 'string', 'confirmed'],
        ];
    }

      /**
     * Summary of messages
     */
    public function messages(): array
    {     
     return [
            'email.unique' => 'The provided email is already registered.',
            'user_name.unique' => 'The provided username is already taken.',
            'password.confirmed' => 'The password confirmation does not match.',
        ];
    }
}
