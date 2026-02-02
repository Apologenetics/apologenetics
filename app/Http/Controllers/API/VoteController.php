<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Vote\VoteDeleteRequest;
use App\Http\Requests\Vote\VoteIndexRequest;
use App\Http\Requests\Vote\VoteStoreRequest;
use App\Http\Requests\Vote\VoteUpdateRequest;
use App\Models\Vote;
use App\Services\VoteService;
use Illuminate\Http\JsonResponse;

class VoteController extends Controller
{
    public function store(VoteStoreRequest $request): JsonResponse
    {
        $vote = VoteService::store(
            $request->validated(),
        );

        return response()->json([
            'vote' => $vote
        ], JsonResponse::HTTP_CREATED);
    }

    public function index(VoteIndexRequest $request): JsonResponse
    {
        $votes = VoteService::index(
            $request->validated(),
        )->simplePaginate();

        return response()->json([
            'votes' => $votes
        ], JsonResponse::HTTP_OK);
    }

    public function update(VoteUpdateRequest $request): JsonResponse
    {
        $votes = VoteService::update(
            $request->validated(),
            $request->votes()
        );

        return response()->json([
            'votes' => $votes
        ], JsonResponse::HTTP_OK);
    }

    public function delete(VoteDeleteRequest $request): JsonResponse
    {
        VoteService::delete($request->votes());

        return response()->json(status: JsonResponse::HTTP_NO_CONTENT);
    }
}
