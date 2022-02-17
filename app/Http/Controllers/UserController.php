<?php

namespace App\Http\Controllers;

use App\Models\Denomination;
use App\Models\Religion;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function viewUser(User $user)
    {
        echo $user->name;
    }

    public function edit(Request $request, ?User $user = null, ?int $faith_id = null)
    {
        $user ??= $request->user();

        $user->load(['allFaiths.denomination', 'allFaiths.religion']);

        return view('users.edit', [
            'user' => $user,
            'religions' => Religion::query()->where('approved', true)->get(),
            'denominations' => Denomination::query()->where('approved', true)->get(),
            'faith_id' => $faith_id
        ]);
    }
}
