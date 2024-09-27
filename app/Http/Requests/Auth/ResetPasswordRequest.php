<?php
namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;

class ResetPasswordRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'token'    => ['required'],
            'email'    => ['required', 'email'],
            'password' => [
                'required',
                'confirmed',
                Rules\Password::min(8) // Minimum 8 characters
                    ->mixedCase()      // Requires both uppercase and lowercase letters
                    ->letters()        // Requires at least one letter
                    ->numbers()        // Requires at least one number
                    ->symbols()        // Requires at least one special character
                    ->uncompromised(), // Checks against known compromised passwords
            ],
        ];
    }
}
