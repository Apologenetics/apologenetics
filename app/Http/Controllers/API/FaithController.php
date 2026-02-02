<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Faith\FaithDeleteRequest;
use App\Http\Requests\Faith\FaithIndexRequest;
use App\Http\Requests\Faith\FaithStoreRequest;
use App\Http\Requests\Faith\FaithUpdateRequest;
use App\Models\Faith;
use App\Services\FaithService;
use Illuminate\Http\JsonResponse;

class FaithController extends Controller
{
    public function store(FaithStoreRequest $request): JsonResponse
    {
        $faith = FaithService::store(
            $request->validated(),
        );

        return response()->json([
            'faith' => $faith
        ], JsonResponse::HTTP_CREATED);
    }

    public function index(FaithIndexRequest $request): JsonResponse
    {
        $faiths = FaithService::index(
            $request->validated(),
        )->simplePaginate();

        return response()->json([
            'faiths' => $faiths
        ], JsonResponse::HTTP_OK);
    }

    public function show(Faith $faith): JsonResponse
    {
        return response()->json([
            'faith' => $faith
        ], JsonResponse::HTTP_OK);
    }

    public function update(FaithUpdateRequest $request): JsonResponse
    {
        $faiths = FaithService::update(
            $request->validated(),
            $request->faiths()
        );

        return response()->json([
            'faiths' => $faiths
        ], JsonResponse::HTTP_OK);
    }

    public function delete(FaithDeleteRequest $request): JsonResponse
    {
        FaithService::delete($request->faiths());

        return response()->json(status: JsonResponse::HTTP_NO_CONTENT);
    }
}
