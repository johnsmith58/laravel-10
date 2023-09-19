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

    public function update(array $data, int $id): ?Model
    {
        if (!$article = $this->model->find($id)) {
            throw new NotFoundException('Not found article!');
        }

        $article->update($data);

        return $article;
    }

    public function destory(Model $model): bool
    {
        return $model->delete();
    }
}
