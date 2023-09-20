<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Database\Eloquent\Model;

interface UserServiceInterface
{
    public function register(Model $model): Model;

    public function login(array $data): ?Model;
}
