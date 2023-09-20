<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Services\UserService;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;
use Illuminate\Http\Resources\Json\JsonResource;

class UserController extends Controller
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function register(RegisterUserRequest $request): JsonResource
    {
        return new UserResource($this->userService->register($request->getValidatedUser()));
    }

    public function login(LoginUserRequest $request): JsonResource
    {
        return new UserResource($this->userService->login($request->validated()));
    }
}
