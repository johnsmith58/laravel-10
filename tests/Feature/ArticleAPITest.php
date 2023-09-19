<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArticleAPITest extends TestCase
{
    use RefreshDatabase;

    public function testGetAllReturnResponseSuccessfulStatus(): void
    {
        $response = $this->get('/api/articles');

        $response->assertStatus(200);
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

    public function testStoreReturnCorrectStatus(): void
    {
        $response = $this->postJson('api/articles', $this->articleJson());

        $response->assertStatus(201);
    }

    public function testStoreReturnCorrectJsonResponse(): void
    {
        $response = $this->postJson('api/articles', $this->articleJson());

        $response->assertStatus(201);

        $response->assertJsonStructure([
            'data' => $this->articleJsonStructure()
        ]);

        $response->assertJson([
            'data' => $this->articleJson()
        ]);
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
}
