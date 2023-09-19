<?php

declare(strict_types=1);

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface ArticleRepositoryInterface
{
    public function getArticles(): Collection;

    public function storeArticle(Model $model): Model;

    public function findById(int $id): ?Model;

    public function update(Model $model): ?Model;

    public function destory(Model $model): bool;
}