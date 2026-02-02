<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Religion\ReligionDeleteRequest;
use App\Http\Requests\Religion\ReligionIndexRequest;
use App\Http\Requests\Religion\ReligionStoreRequest;
use App\Http\Requests\Religion\ReligionUpdateRequest;
use App\Models\Religion;
use App\Services\ReligionService;
use Illuminate\Http\JsonResponse;

class ReligionController extends Controller
{
    public function store(ReligionStoreRequest $request): JsonResponse
    {
        $religion = ReligionService::store(
            $request->validated(),
        );

        return response()->json([
            'religion' => $religion
        ], JsonResponse::HTTP_CREATED);
    }

    public function index(ReligionIndexRequest $request): JsonResponse
    {
        $religions = ReligionService::index(
            $request->validated(),
        )->simplePaginate();

        return response()->json([
            'religions' => $religions
        ], JsonResponse::HTTP_OK);
    }

    public function show(Religion $religion): JsonResponse
    {
        abort_unless($religion->approved, JsonResponse::HTTP_NOT_FOUND);

        return response()->json([
            'religion' => $religion
        ], JsonResponse::HTTP_OK);
    }

    public function update(ReligionUpdateRequest $request, Religion $religion): JsonResponse
    {
        $religions = ReligionService::update(
            $religion,
            $request->validated(),
        );

        return response()->json([
            'religions' => $religions
        ], JsonResponse::HTTP_OK);
    }

    public function delete(ReligionDeleteRequest $request): JsonResponse
    {
        ReligionService::delete($request->religions());

        return response()->json(status: JsonResponse::HTTP_NO_CONTENT);
    }
}
