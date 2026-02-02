<?php

namespace App\Http\Controllers;

use App\Http\Requests\Doctrine\DoctrineStoreRequest;
use App\Services\DoctrineService;
use Illuminate\Http\JsonResponse;

class DoctrineController extends Controller
{
    public function store(DoctrineStoreRequest $request): JsonResponse
    {
        return response()->json([
            'doctrine' => DoctrineService::store($request->validated()),
            JsonResponse::HTTP_OK,
        ]);
    }
}
