<?php

declare(strict_types=1);

namespace App\Repositories;

use Exception;
use App\Models\User;
use App\Repositories\Repository;
use Illuminate\Database\Eloquent\Model;
use App\Http\Exceptions\DbErrorException;
use App\Repositories\UserRepositoryInterface;
use Illuminate\Http\Request;

class UserRepository extends Repository implements UserRepositoryInterface
{
    protected function model(): string
    {
        return User::class;
    }

    public function register($model): Model
    {
        try {
            tap(($model), fn () => $model->save());
        } catch (Exception $e) {
            throw new DbErrorException($e->getMessage(), 500);
        }

        return $model;
    }

    public function login($data): Model
    {
        return User::find($data);
    }

    public function findByEmail($email): ?Model
    {
        return $this->model->where('email', $email)->first();
    }
}
