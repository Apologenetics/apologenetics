<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tag\TagDeleteRequest;
use App\Http\Requests\Tag\TagIndexRequest;
use App\Http\Requests\Tag\TagStoreRequest;
use App\Http\Requests\Tag\TagUpdateRequest;
use App\Models\Tag;
use App\Services\TagService;
use Illuminate\Http\JsonResponse;

class TagController extends Controller
{
    public function store(TagStoreRequest $request): JsonResponse
    {
        $tag = TagService::store(
            $request->validated(),
        );

        return response()->json([
            'tag' => $tag
        ], JsonResponse::HTTP_CREATED);
    }

    public function index(TagIndexRequest $request): JsonResponse
    {
        $tags = TagService::index(
            $request->validated(),
        )->simplePaginate();

        return response()->json([
            'tags' => $tags
        ], JsonResponse::HTTP_OK);
    }

    public function show(Tag $tag): JsonResponse
    {
        return response()->json([
            'tag' => $tag
        ], JsonResponse::HTTP_OK);
    }

    public function update(TagUpdateRequest $request): JsonResponse
    {
        $tags = TagService::update(
            $request->validated(),
            $request->tags()
        );

        return response()->json([
            'tags' => $tags
        ], JsonResponse::HTTP_OK);
    }

    public function delete(TagDeleteRequest $request): JsonResponse
    {
        TagService::delete($request->tags());

        return response()->json(status: JsonResponse::HTTP_NO_CONTENT);
    }
}
