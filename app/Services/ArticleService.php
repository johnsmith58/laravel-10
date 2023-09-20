<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use App\Services\ArticleServiceInterface;
use App\Http\Exceptions\NotFoundException;
use App\Repositories\ArticleRepositoryInterface;

class ArticleService implements ArticleServiceInterface
{
    private ArticleRepositoryInterface $articleRepository;

    public function __construct(ArticleRepositoryInterface $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    public function getArticles(): Collection
    {
        return $this->articleRepository->getArticles();
    }

    public function storeArticle(Model $model): Model
    {
        return $this->articleRepository->storeArticle($model);
    }

    public function showById(int $id): Model
    {
        if (!$article = $this->articleRepository->findById($id)) {
            throw new NotFoundException('Not found article!', 404);
        }

        return $article;
    }

    public function updateArticle(Model $model, int $id): ?Model
    {
        if (!$article = $this->articleRepository->findById($id)) {
            throw new NotFoundException('Not found article!', 404);
        }

        return $this->articleRepository->update($article->fill($model->toArray()));
    }

    public function destoryById(int $id): bool
    {
        if (!$article = $this->articleRepository->findById($id)) {
            throw new NotFoundException('Not found article!', 404);
        }

        return $this->articleRepository->destory($article);
    }
}
