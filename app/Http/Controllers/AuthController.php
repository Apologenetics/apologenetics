<?php

namespace App\Http\Controllers;

use App\Models\Religion;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class AuthController extends Controller
{
    public function register(): InertiaResponse
    {
        return Inertia::render('auth/Register', [
            'religions' => Inertia::defer(function () {
                return array_merge(
                    [['id' => 0, 'name' => 'Other']],
                    Religion::select('id', 'name')->get()->toArray()
                );
            })
        ]);
    }
}
