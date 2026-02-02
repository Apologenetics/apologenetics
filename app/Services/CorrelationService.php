<?php

namespace App\Services;

use App\Models\Correlation;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

class CorrelationService
{
    public static function store(array $params): Correlation
    {
        $correlation = new Correlation();
        $correlation->forceFill($params)->save();

        return $correlation;
    }

    public static function index(array $params): Builder
    {
        return Correlation::query();
    }

    public static function update(array $params, EloquentCollection $correlations): EloquentCollection
    {
        foreach ($correlations as $correlation) {
            $correlation->forceFill($params['data'])->save();
        }

        return $correlations;
    }

    public static function delete(EloquentCollection $correlations): void
    {
        foreach ($correlations as $correlation) {
            Correlation::query()
                ->where('correlatable_from_type', $correlation->correlatable_from_type)
                ->where('correlatable_from_id', $correlation->correlatable_from_id)
                ->where('correlatable_to_type', $correlation->correlatable_to_type)
                ->where('correlatable_to_id', $correlation->correlatable_to_id)
                ->delete();
        }
    }

    public static function canUpdateAll(EloquentCollection $correlations, User $user): bool
    {
        return true;
    }

    public static function canDeleteAll(EloquentCollection $correlations, User $user): bool
    {
        return true;
    }
}
