<?php

namespace App\Http\Controllers\api;

use App\Services\ArticleService;
use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleResource;
use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;

class ArticleController extends Controller
{
    private ArticleService $articleService;

    public function __construct(ArticleService $articleService)
    {
        $this->articleService = $articleService;
    }

    public function index(): JsonResource
    {
        return ArticleResource::collection($this->articleService->getArticles());
    }

    public function store(StoreArticleRequest $request): ?JsonResource
    {
        return new ArticleResource($this->articleService->storeArticle($request->getValidatedArticle()));
    }

    public function show(int $id): ?JsonResource
    {
        return new ArticleResource($this->articleService->showById($id));
    }

    public function update(UpdateArticleRequest $request, int $id): ?JsonResource
    {
        return new ArticleResource($this->articleService->updateArticle($request->getValidatedArticle(), $id));
    }

    public function destroy(int $id): Response
    {
        if($this->articleService->destoryById($id))
        {
            return response(['message' => 'Article delete successfully!'], 200);
        }

        return response(['message' => 'Article delete unsuccessfully!'], 500);
    }
}
