<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
                'Introduction' => 'sometimes|max:255',
                'LookingFor' => 'sometimes|max:255',
                'Interests' => 'sometimes|max:255',
                'City' => 'sometimes|max:255',
                'Country' => 'sometimes|max:255',
        ];
    }
}
