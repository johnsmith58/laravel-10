<?php

declare(strict_types=1);

namespace App\Providers;

use App\Services\UserService;
use App\Services\UserServiceInterface;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

final class BindUserServiceServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public function register(): void
    {
        $this->app->bind(
            UserServiceInterface::class,
            UserService::class
        );
    }

    public function provides(): array
    {
        return [UserServiceInterface::class];
    }
}
