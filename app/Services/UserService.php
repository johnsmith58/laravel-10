<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;
use App\Http\Exceptions\NotFoundException;
use App\Repositories\UserRepositoryInterface;
use App\Http\Exceptions\UnAuthenticationException;

class UserService implements UserServiceInterface
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function register(Model $model): Model
    {
        $user = $this->userRepository->register($model);

        //add User token with sanctum
        $user['token'] = $this->generateToken($user);

        return $user;
    }

    protected function generateToken($user): string
    {
        return $user->createToken('MyApp')->plainTextToken;
    }

    public function login(array $data): ?Model
    {
        if (!$user = $this->userFindByEmail($data['email'])) {
            throw new NotFoundException('User not found');
        }

        if (!$this->hashPasswordCheck($user, $data['password'])) {
            throw new UnAuthenticationException('User crendetial not correct!', 401);
        }

        $user['token'] = $this->generateToken($user);

        return $user;
    }

    protected function hashPasswordCheck($user, $password): bool
    {
        return Hash::check($password, $user->password);
    }

    protected function userFindByEmail($email): ?Model
    {
        return $this->userRepository->findByEmail($email);
    }
}
