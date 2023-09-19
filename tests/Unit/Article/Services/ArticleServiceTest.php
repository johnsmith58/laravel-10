<?php

declare(strict_types=1);

namespace Tests\Unit\Article\Services;

use App\Http\Exceptions\DbErrorException;
use App\Http\Exceptions\NotFoundException;
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

    public function testCanShowById(): void
    {
        $this->ensureFindByIdCall(true);

        $result = $this->articleService->showById(1);

        $this->assertInstanceOf(Article::class, $result);
    }

    public function testCanHandleNotFoundShowById(): void
    {
        $this->ensureFindByIdCall(false);

        $this->expectException(NotFoundException::class);

        $this->articleService->showById(2);
    }

    protected function ensureFindByIdCall(bool $shouldFound): void
    {
        $this->articleRepository->shouldReceive('findById')
            ->once()->andReturn($shouldFound ? new Article() : null);
    }

    public function testCanUpdateArticle(): void
    {
        $this->ensureFindByIdCall(true);

        $this->ensureUpdateCall();

        $result = $this->articleService->updateArticle($this->articleArrayData(), 1);

        $this->assertInstanceOf(Article::class, $result);
    }

    public function testCanHandleNotFoundUpdateArticle(): void
    {
        $this->ensureFindByIdCall(false);

        $this->expectException(NotFoundException::class);

        $this->articleService->updateArticle($this->articleArrayData(), 2);
    }

    protected function ensureUpdateCall(): void
    {
        $this->articleRepository->shouldReceive('update')->once()->andReturn(new Article());
    }

    protected function articleArrayData(): array
    {
        return [
            "title" => "News Poster",
            "sub_title" => "The wave is coming on December 15th",
            "content" => "Recusandae quas esse sint distinctio facilis quo.",
            "img_src" => "https://via.placeholder.com/640x480.png/0011aa?text=maxime",
            "author_name" => "Cassie Ryan III",
            "order_no" => 71
        ];
    }

    public function testCanDestoryById(): void
    {
        $this->ensureFindByIdCall(true);
        $this->ensureDestoryCall();

        $result = $this->articleService->destoryById(1);

        $this->assertTrue($result);
    }

    public function testCanHandleNotFoundDestoryById(): void
    {
        $this->ensureFindByIdCall(false);

        $this->expectException(NotFoundException::class);

        $this->articleService->destoryById(1);
    }

    protected function ensureDestoryCall(): void
    {
        $this->articleRepository->shouldReceive('destory')->once()->andReturn(true);
    }
}
