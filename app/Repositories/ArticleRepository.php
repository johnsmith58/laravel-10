<?php

declare(strict_types=1);

namespace App\Repositories;

use Exception;
use App\Models\Article;
use App\Repositories\Repository;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use App\Http\Exceptions\DbErrorException;
use App\Http\Exceptions\NotFoundException;

class ArticleRepository extends Repository implements ArticleRepositoryInterface
{
    protected function model(): string
    {
        return Article::class;
    }

    public function getArticles(): Collection
    {
        return $this->model->get();
    }

    public function storeArticle(Model $model): Model
    {
        try {
            $model->save();
        } catch (Exception $e) {
            throw new DbErrorException($e->getMessage());
        }
        return $model;
    }

    public function findById(int $id): ?Model
    {
        return $this->model->find($id);
    }

    public function update(Model $model): ?Model
    {
        return tap(($model), fn () => $model->update());
    }

    public function destory(Model $model): bool
    {
        return $model->delete();
    }
}
