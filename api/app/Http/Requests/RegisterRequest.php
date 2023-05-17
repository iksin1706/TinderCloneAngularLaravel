<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'username' => 'required|string',
            'knownAs' => 'required|string',
            'gender' => 'required|string',
            'dateOfBirth' => 'required|date_format:Y-m-d',
            'city' => 'required|string',
            'country' => 'required|string',
            'password' => 'required|string|min:4|max:8',
        ];
    }
}
