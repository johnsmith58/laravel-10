<?php

declare(strict_types=1);

namespace Tests\Unit\Article\Services;

use App\Http\Exceptions\DbErrorException;
use App\Models\Article;
use App\Repositories\ArticleRepositoryInterface;
use App\Services\ArticleService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Mockery;
use PHPUnit\Framework\TestCase;
use Tests\CleanUpTrait;

class ArticleServiceTest extends TestCase
{
    use CleanUpTrait;

    private ArticleRepositoryInterface $articleRepository;

    private ArticleService $articleService;

    protected function setup(): void
    {
        $this->articleRepository = Mockery::mock(ArticleRepositoryInterface::class);
        $this->articleService = new ArticleService(
            $this->articleRepository
        );
    }

    public function testArticleServiceCanInitiate(): void
    {
        $this->assertInstanceOf(ArticleService::class, $this->articleService);
    }

    public function testArticleServiceCanGetArticles(): void
    {
        $this->ensureGetArticles();

        $result = $this->articleService->getArticles();

        $this->assertInstanceOf(Collection::class, $result);
    }

    private function ensureGetArticles(): void
    {
        $this->articleRepository->shouldReceive('getArticles')->once()->andReturn(new Collection());
    }

    public function testArticleServiveCanStoreArticle(): void
    {
        $this->ensureStoreArticleCall();

        $result = $this->articleService->storeArticle(new Article());

        $this->assertInstanceOf(Article::class, $result);
    }

    protected function ensureStoreArticleCall(): void
    {
        $this->articleRepository->shouldReceive('storeArticle')->once()->andReturn(new Article());
    }

    public function testArticleServiceStoreArticleCanHandleIfFail(): void
    {
        $this->ensureStoreArticleThrowException();

        $this->expectException(DbErrorException::class);

        $this->articleService->storeArticle(new Article());
    }

    protected function ensureStoreArticleThrowException(): void
    {
        $this->articleRepository->shouldReceive('storeArticle')->once()->andThrow(new DbErrorException());
    }
}
