<?php

namespace App\Http\Requests\Api\Post;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('post'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|min:5|max:255|regex:/^[A-Za-z0-9\s\S]*[A-Za-z][A-Za-z0-9\s\S]*$/i',
            'body' => 'required|string|min:50|regex:/^[A-Za-z0-9\s\S]*[A-Za-z][A-Za-z0-9\s\S]*$/i',
        ];
    }
}
