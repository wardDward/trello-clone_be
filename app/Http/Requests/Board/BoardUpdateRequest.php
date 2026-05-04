<?php

namespace App\Http\Requests\Board;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class BoardUpdateRequest extends FormRequest
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
        ];
    }

    public function messages(): array
    {
        return [
            'name.max' => 'The board name may not be greater than 255 characters.',
            'visibility.in' => 'The visibility must be one of the following: PRIVATE, PUBLIC, WORKSPACE.',
        ];
    }
}
