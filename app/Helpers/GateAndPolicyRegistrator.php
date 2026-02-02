<?php

namespace App\Helpers;

use App\Models\Correlation;
use App\Models\Doctrine;
use App\Models\Faith;
use App\Models\Religion;
use App\Models\Tag;
use App\Models\Vote;
use App\Policies\CorrelationPolicy;
use App\Policies\DoctrinePolicy;
use App\Policies\FaithPolicy;
use App\Policies\ReligionPolicy;
use App\Policies\TagPolicy;
use App\Policies\VotePolicy;
use Illuminate\Support\Facades\Gate;

class GateAndPolicyRegistrator
{
    public static function registerGates(): void
    {
        //
    }

    public static function registerPolicies(): void
    {
        Gate::policy(Religion::class, ReligionPolicy::class);
        Gate::policy(Doctrine::class, DoctrinePolicy::class);
        Gate::policy(Faith::class, FaithPolicy::class);
        Gate::policy(Tag::class, TagPolicy::class);
        Gate::policy(Correlation::class, CorrelationPolicy::class);
        Gate::policy(Vote::class, VotePolicy::class);
    }

    public static function register(): void
    {
        self::registerGates();
        self::registerPolicies();
    }
}
