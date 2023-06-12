<?php

namespace App\Http\Requests;

use Carbon\Carbon;
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
            'username' => 'required|string|unique:users',
            'knownAs' => 'required|string|min:2|max:32',
            'gender' => 'required|in:female,male',
            'dateOfBirth' => 'required|date_format:Y-m-d|before_or_equal:' . Carbon::now()->subYears(18)->format('Y-m-d'),
            'city' => 'required|string|min:2|max:32',
            'country' => 'required|string|min:2|max:32',
            'password' => 'required|string|min:6|max:16',
            'email' => 'required|string|email|unique:users,email',
        ];
    }
}
