<?php

declare(strict_types=1);

namespace Tests\Unit\Article\Requests;

use App\Http\Requests\StoreArticleRequest;
use Illuminate\Foundation\Http\FormRequest;
use PHPUnit\Framework\TestCase;

class StoreArticleRequestTest extends TestCase
{
    private StoreArticleRequest $storeArticleRequest;

    protected function setUp(): void
    {
        $this->storeArticleRequest = new StoreArticleRequest();
    }

    public function testStoreArticleRequestCanInitiate(): void
    {
        $this->assertInstanceOf(StoreArticleRequest::class, $this->storeArticleRequest);
        $this->assertInstanceOf(FormRequest::class, $this->storeArticleRequest);
    }

    public function testStoreArticleRequestShouldReturnRule(): void
    {
        $rules = $this->storeArticleRequest->rules();

        $this->assertCount(6, $rules);

        $this->assertArrayHasKey('title', $rules);
        $this->assertArrayHasKey('sub_title', $rules);
        $this->assertArrayHasKey('content', $rules);
        $this->assertArrayHasKey('img_src', $rules);
        $this->assertArrayHasKey('author_name', $rules);
        $this->assertArrayHasKey('order_no', $rules);

        $this->assertContains('required', $rules['title']);
        $this->assertContains('string', $rules['title']);
        $this->assertContains('min:5', $rules['title']);
        $this->assertContains('max:30', $rules['title']);

        $this->assertContains('required', $rules['sub_title']);
        $this->assertContains('string', $rules['sub_title']);
        $this->assertContains('min:5', $rules['sub_title']);
        $this->assertContains('max:50', $rules['sub_title']);

        $this->assertContains('required', $rules['content']);
        $this->assertContains('string', $rules['content']);
        $this->assertContains('min:5', $rules['content']);
        $this->assertContains('max:255', $rules['content']);

        $this->assertContains('required', $rules['img_src']);
        $this->assertContains('url:https', $rules['img_src']);

        $this->assertContains('required', $rules['author_name']);
        $this->assertContains('string', $rules['author_name']);
        $this->assertContains('min:5', $rules['author_name']);
        $this->assertContains('max:30', $rules['author_name']);

        $this->assertContains('required', $rules['order_no']);
        $this->assertContains('integer', $rules['order_no']);
    }
}
