<?php

declare(strict_types=1);

namespace App\Providers;

use App\Services\ArticleService;
use App\Services\ArticleServiceInterface;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

final class BindArticleServiceServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public function register(): void
    {
        $this->app->bind(
            ArticleServiceInterface::class,
            ArticleService::class
        );
    }

    public function provides(): array
    {
        return [ArticleServiceInterface::class];
    }
}
