<?php

namespace App\Policies;

use App\Models\User;

class CorrelationPolicy
{
    public function create(User $user): bool
    {
        return $user->hasVerifiedEmail();
    }

    public function viewAny(User|null $user): bool
    {
        return true;
    }
}
