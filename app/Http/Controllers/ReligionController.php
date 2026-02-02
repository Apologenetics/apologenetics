<?php

namespace App\Http\Controllers;

use App\Http\Requests\Religion\ReligionIndexRequest;
use App\Http\Requests\Religion\ReligionStoreRequest;
use App\Http\Requests\Religion\ReligionUpdateRequest;
use App\Models\Religion;
use App\Services\ReligionService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class ReligionController extends Controller
{
    public function store(ReligionStoreRequest $request): RedirectResponse
    {
        ReligionService::store($request->validated());

        return back(201)->with('message', 'Religion created successfully.');
    }

    public function index(ReligionIndexRequest $request): InertiaResponse
    {
        return inertia('religion/Index', [
            'religions' => Inertia::defer(function () use ($request) {
                return ReligionService::index(
                    $request->validated(),
                )->simplePaginate();
            })
        ]);
    }

    public function show(Religion $religion): InertiaResponse
    {
        $religion = ReligionService::show($religion);

        return inertia('religion/Show', [
            'religion' => Inertia::defer(function () use ($religion) {
                return $religion;
            }),
        ]);
    }

    public function update(ReligionUpdateRequest $request, Religion $religion): RedirectResponse
    {
        ReligionService::update($religion, $request->validated());

        return back()->with('message', 'Religion updated successfully.');
    }

    public function doctrines(Religion $religion): InertiaResponse
    {
        return inertia('religion/Doctrines', [
            'religion' => $religion,
            'doctrines' => Inertia::defer(function () use ($religion) {
                return $religion->doctrines()->simplePaginate();
            })
        ]);
    }
}
