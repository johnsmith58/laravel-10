<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\ArticleRepositoryInterface;
use App\Http\Exceptions\FailStoreArticleException;
use App\Http\Exceptions\NotFoundException;
use Exception;

class ArticleService
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
            throw new NotFoundException('Not found article!');
        }

        return $article;
    }

    public function updateArticle(array $array, int $id): ?Model
    {
        if (!$this->articleRepository->findById($id)) {
            throw new NotFoundException('Not found article!');
        }

        return $this->articleRepository->update($array, $id);
    }

    public function destoryById(int $id): bool
    {
        if (!$article = $this->articleRepository->findById($id)) {
            throw new NotFoundException('Not found article!');
        }

        return $this->articleRepository->destory($article);
    }
}
