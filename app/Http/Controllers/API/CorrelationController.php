<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Correlation\CorrelationDeleteRequest;
use App\Http\Requests\Correlation\CorrelationIndexRequest;
use App\Http\Requests\Correlation\CorrelationStoreRequest;
use App\Http\Requests\Correlation\CorrelationUpdateRequest;
use App\Models\Correlation;
use App\Services\CorrelationService;
use Illuminate\Http\JsonResponse;

class CorrelationController extends Controller
{
    public function store(CorrelationStoreRequest $request): JsonResponse
    {
        $correlation = CorrelationService::store(
            $request->validated(),
        );

        return response()->json([
            'correlation' => $correlation
        ], JsonResponse::HTTP_CREATED);
    }

    public function index(CorrelationIndexRequest $request): JsonResponse
    {
        $correlations = CorrelationService::index(
            $request->validated(),
        )->simplePaginate();

        return response()->json([
            'correlations' => $correlations
        ], JsonResponse::HTTP_OK);
    }

    public function delete(CorrelationDeleteRequest $request): JsonResponse
    {
        CorrelationService::delete($request->correlations());

        return response()->json(status: JsonResponse::HTTP_NO_CONTENT);
    }
}
