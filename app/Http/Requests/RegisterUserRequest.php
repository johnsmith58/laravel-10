<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class RegisterUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:5', 'max:40'],
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:6', 'max:20'],
            'confirm_password' => ['required', 'same:password', 'string', 'min:6', 'max:20']
        ];
    }

    public function getValidatedUser(): User
    {
        return new User($this->validated());
    }
}
