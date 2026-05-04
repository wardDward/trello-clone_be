<?php

namespace App\Http\Requests\Board;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class BoardStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'visibility' => ['required', 'in:PRIVATE,PUBLIC,WORKSPACE'],
            'members' => ['nullable', 'array'],
            'members.*.uuid' => ['required', Rule::exists('users', 'uuid')],
            'members.*.role' => ['required', 'in:ADMIN,MEMBER,VIEWER'],
        ];
    }


    public function messages(): array
    {
        return [
            'name.max' => 'The board name may not be greater than 255 characters.',
            'visibility.in' => 'The visibility must be one of the following: PRIVATE, PUBLIC, WORKSPACE.',
            'members.*.uuid.required' => 'Each member must have a valid UUID.',
            'members.*.uuid.exists' => 'Each member UUID must exist in the users table.',
            'members.*.role.required' => 'Each member must have a role.',
            'members.*.role.in' => 'Each member role must be one of the following: ADMIN, MEMBER, VIEWER.',
        ];
    }
}
