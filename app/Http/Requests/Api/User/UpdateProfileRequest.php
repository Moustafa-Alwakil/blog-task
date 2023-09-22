<?php

namespace App\Http\Requests\Api\User;

use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'max:255', Rule::unique(User::class, 'email')->ignore(auth()->user())],
            'current_password' => ['nullable', Rule::requiredIf($this->filled('new_password')), Password::defaults(), 'current_password:sanctum'],
            'new_password' => ['nullable', Password::defaults(), 'confirmed'],
            'name' => 'required|string|max:255',
            'avatar' => 'nullable|image|max:4096|mimes:png,jpg,jpeg',
        ];
    }
}
