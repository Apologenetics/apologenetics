<?php

namespace App\Policies;

use App\Models\Religion;
use App\Models\User;

class ReligionPolicy
{
    public function create(User $user): bool
    {
        return $user->hasVerifiedEmail();
    }

    public function viewAny(User|null $user): bool
    {
        return true;
    }

    public function update(User $user, Religion $religion): bool
    {
        return true;
    }
}
