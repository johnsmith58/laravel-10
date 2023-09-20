<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Hash;

class HashService
{
    public function hashPasswordCheck($user, $password): bool
    {
        return Hash::check($password, $user->password);
    }
}