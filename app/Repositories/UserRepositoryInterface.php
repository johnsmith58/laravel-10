<?php

declare(strict_types=1);

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

interface UserRepositoryInterface
{
    public function register($model): Model;

    public function login($data): Model;

    public function findByEmail($email): ?Model;
}
