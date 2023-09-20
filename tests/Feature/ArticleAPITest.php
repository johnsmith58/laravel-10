<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Article;
use Illuminate\Http\Response;
use App\Http\Exceptions\NotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ArticleAPITest extends TestCase
{
    use RefreshDatabase;

    public function testGetAllReturnResponseSuccessfulStatus(): void
    {
        $response = $this->get('/api/articles');

        $response->assertStatus(Response::HTTP_OK);
    }

    public function testGetAllCorrectJsonStructure(): void
    {
        Article::factory()->count(10)->create();

        $response = $this->getJson('api/articles');

        $response->assertJsonStructure([
            'data' => [
                '*' => $this->articleJsonStructure()
            ]
        ]);
    }

    public function testGetAllCorrectJsonResponse(): void
    {
        $articles = Article::factory()->count(1)->create();

        $response = $this->getJson('api/articles');

        $response->assertJsonFragment($articles->toArray());
    }

    public function testCanStoreReturnCorrectStatus(): void
    {
        $this->postJson('api/articles', $this->articleJson())->assertStatus(201);
    }

    public function testCanStoreReturnCorrectJsonResponse(): void
    {
        $response = $this->postJson('api/articles', $this->articleJson());

        $response->assertStatus(Response::HTTP_CREATED);

        $response->assertJsonStructure([
            'data' => $this->articleJsonStructure()
        ]);

        $response->assertJson([
            'data' => $this->articleJson()
        ]);
    }

    public function testStoreCanHandleOnValidation(): void
    {
        $response = $this->postJson("api/articles");

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $response->assertJsonStructure(['errors']);

        $response->assertJsonValidationErrors($this->articleValidationArr(), 'errors');
    }

    private function articleJson(): array
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

    private function articleJsonStructure(): array
    {
        return [
            'id',
            'title',
            'sub_title',
            'content',
            'img_src',
            'author_name',
            'order_no',
            'created_at',
            'updated_at'
        ];
    }

    public function testCanShowReturnCorrectStatus(): void
    {
        $article = $this->createArticle();

        $this->getJson("api/articles/{$article->id}")->assertStatus(Response::HTTP_OK);
    }

    private function createArticle(): Article
    {
        return Article::factory()->create();
    }

    public function testCanShowReturnCorrectJsonResponse(): void
    {
        $article = $this->createArticle();

        $response = $this->getJson("api/articles/{$article->id}");

        $response->assertStatus(200);

        $response->assertJson([
            'data' => $article->toArray()
        ]);
    }

    public function testCanShowReturnCorrectJsonStructure(): void
    {
        $article = $this->createArticle();

        $response = $this->getJson("api/articles/{$article->id}");

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'data' => $this->articleJsonStructure()
        ]);
    }

    public function testCanHanldeNotFoundShow(): void
    {
        $response = $this->getJson('api/articles/2');

        $response->assertStatus(Response::HTTP_NOT_FOUND);

        $response->assertJsonStructure(['error' => [
            'message'
        ]]);
    }

    public function testUpdateCanHandle(): void
    {
        $article = $this->createArticle();

        $response = $this->putJson("api/articles/{$article->id}", $this->articleJson());

        $response->assertStatus(200);
    }

    public function testUpdateReturnCorrectResponse(): void
    {
        $article = $this->createArticle();

        $response = $this->putJson("api/articles/{$article->id}", $this->articleJson());

        $response->assertStatus(Response::HTTP_OK);

        $response->assertJson([
            'data' => $this->articleJson()
        ]);

        $response->assertJsonStructure([
            'data' => $this->articleJsonStructure()
        ]);
    }

    public function testUpdateCanHandleValidation(): void
    {
        $article = $this->createArticle();

        $response = $this->putJson("api/articles/{$article->id}");

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $response->assertJsonStructure(['errors']);

        $response->assertJsonValidationErrors($this->articleValidationArr(), 'errors');
    }

    public function testUpdateCanHandleNotFound(): void
    {
        $response = $this->putJson("api/articles/2", $this->articleJson());
        
        $response->assertStatus(Response::HTTP_NOT_FOUND);

        $response->assertJsonStructure(['error' => [
            'message'
        ]]);
    }

    protected function articleValidationArr(): array
    {
        return ['title', 'sub_title', 'content', 'img_src', 'author_name', 'order_no'];
    }

    public function testCanDestoryReturnCorrectStatusAndMessage(): void
    {
        $article = $this->createArticle();

        $response = $this->deleteJson("api/articles/{$article->id}");
        
        $response->assertStatus(Response::HTTP_OK);

        $response->assertExactJson(["message" => "Article delete successfully!"]);
    }

    public function testDestoryCanHandleNotFoundAndReturnCorrectStatusAndMessage(): void
    {
        $response = $this->deleteJson("api/articles/3");

        $response->assertStatus(Response::HTTP_NOT_FOUND);

        $response->assertJsonStructure(['error' => [
            'message'
        ]]);

        $response->assertExactJson(['error' => [
            'message' => 'Not found article!'
        ]]);
    }
}
