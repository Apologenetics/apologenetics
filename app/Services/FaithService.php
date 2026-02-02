<?php

namespace App\Services;

use App\Models\Faith;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

class FaithService
{
    public static function store(array $params): Faith
    {
        $faith = new Faith();
        $faith->forceFill($params)->save();

        return $faith;
    }

    public static function index(array $params): Builder
    {
        return Faith::query();
    }

    public static function update(array $params, EloquentCollection $faiths): EloquentCollection
    {
        Faith::query()
            ->whereIn('id', $faiths->pluck('id'))
            ->update($params['data']);

        $faiths->each(function (Faith $faith) use ($params) {
            $faith->forceFill($params['data']);
        });

        return $faiths;
    }

    public static function delete(EloquentCollection $faiths): void
    {
        Faith::query()
            ->whereIn('id', $faiths->pluck('id'))
            ->delete();
    }

    public static function canUpdateAll(EloquentCollection $faiths, User $user): bool
    {
        return true;
    }

    public static function canDeleteAll(EloquentCollection $faiths, User $user): bool
    {
        return true;
    }
}
