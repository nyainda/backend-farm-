<?php
namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email' => [
                'required',
                'email',
                'max:255',
                'exists:users,email' // Ensures the email exists in the users table
            ],
            'password' => [
                'required',
                'string',
                'min:8', // Minimum 8 characters for stronger password
                'max:64' // Optional maximum length constraint
            ],
        ];
    }
}

