<?php

declare(strict_types=1);

namespace Tests\Unit\Article\Repositories;

use App\Models\Article;
use App\Repositories\ArticleRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Collection;
use Mockery;
use PHPUnit\Framework\TestCase;
use Tests\CleanUpTrait;

class ArticleRepositoryTest extends TestCase
{
    use CleanUpTrait;
    use DatabaseTransactions;

    private Application $appMock;
    private Article $articleModelMock;
    private ArticleRepository $articleRepository;

    public function setUp(): void
    {
        $this->appMock = Mockery::mock(Application::class);
        $this->articleModelMock = Mockery::mock(Article::class);

        $this->setUpAppMockException();

        $this->articleRepository = new ArticleRepository($this->appMock);
    }

    private function setUpAppMockException()
    {
        $this->appMock->shouldReceive('make')
            ->with(Article::class)
            ->andReturn($this->articleModelMock);
    }

    public function testGetArticles(): void
    {
        $this->ensureToCallgetArticleModel();

        $this->assertInstanceOf(Collection::class, $this->articleRepository->getArticles());
    }

    public function ensureToCallgetArticleModel()
    {
        $this->articleModelMock->shouldReceive('get')->once()->andReturn(new Collection());
    }

    public function testStoreArticle(): void
    {
        $this->ensureToCallStoreArticleModel();

        $result = $this->articleRepository->storeArticle($this->articleModelMock);

        $this->assertEquals($this->articleModelMock, $result);
    }

    private function ensureToCallStoreArticleModel(): void
    {
        $this->articleModelMock->shouldReceive('save')->once();
    }
}
