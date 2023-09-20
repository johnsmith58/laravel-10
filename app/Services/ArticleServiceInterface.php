<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

interface ArticleServiceInterface
{
    public function getArticles(): Collection;

    public function storeArticle(Model $model): Model;

    public function showById(int $id): Model;

    public function updateArticle(Model $model, int $id): ?Model;

    public function destoryById(int $id): bool;
}
