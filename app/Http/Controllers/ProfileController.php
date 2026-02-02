<?php

namespace App\Http\Controllers;

use App\Http\Resources\OtherUserResource;
use App\Models\User;
use App\Services\UserService;
use Inertia\Response as InertiaResponse;

class ProfileController extends Controller
{
    public function profile(User $user): InertiaResponse
    {
        return inertia('Profile', [
            'user' => OtherUserResource::make(
                UserService::show($user),
            ),
        ]);
    }
}
