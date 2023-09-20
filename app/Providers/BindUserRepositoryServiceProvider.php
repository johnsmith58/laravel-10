<?php

declare(strict_types=1);

namespace App\Providers;

use App\Repositories\UserRepository;
use App\Repositories\UserRepositoryInterface;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

final class BindUserRepositoryServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public function register(): void
    {
        $this->app->bind(
            UserRepositoryInterface::class,
            UserRepository::class
        );
    }

    public function provides(): array
    {
        return [UserRepositoryInterface::class];
    }
}