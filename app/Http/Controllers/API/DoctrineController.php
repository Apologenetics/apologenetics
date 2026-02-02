<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Doctrine\DoctrineDeleteRequest;
use App\Http\Requests\Doctrine\DoctrineIndexRequest;
use App\Http\Requests\Doctrine\DoctrineStoreRequest;
use App\Http\Requests\Doctrine\DoctrineUpdateRequest;
use App\Models\Doctrine;
use App\Services\DoctrineService;
use Illuminate\Http\JsonResponse;

class DoctrineController extends Controller
{
    /**
     * @throws \Throwable
     */
    public function store(DoctrineStoreRequest $request): JsonResponse
    {
        $doctrine = DoctrineService::store(
            $request->validated(),
        );

        return response()->json([
            'doctrine' => $doctrine
        ], JsonResponse::HTTP_CREATED);
    }

    public function index(DoctrineIndexRequest $request): JsonResponse
    {
        $doctrines = DoctrineService::index(
            $request->validated(),
        )->simplePaginate();

        return response()->json([
            'doctrines' => $doctrines
        ], JsonResponse::HTTP_OK);
    }

    public function show(Doctrine $doctrine): JsonResponse
    {
        return response()->json([
            'doctrine' => $doctrine
        ], JsonResponse::HTTP_OK);
    }

    public function update(DoctrineUpdateRequest $request): JsonResponse
    {
        $doctrines = DoctrineService::update(
            $request->validated(),
            $request->doctrines()
        );

        return response()->json([
            'doctrines' => $doctrines
        ], JsonResponse::HTTP_OK);
    }

    public function delete(DoctrineDeleteRequest $request): JsonResponse
    {
        DoctrineService::delete($request->doctrines());

        return response()->json(status: JsonResponse::HTTP_NO_CONTENT);
    }
}
