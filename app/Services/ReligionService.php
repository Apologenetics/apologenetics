<?php

namespace App\Services;

use App\Models\Religion;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Facades\Cache;

class ReligionService
{
    public static function store(array $params): Religion
    {
        $religion = new Religion();
        $religion->forceFill($params)->save();

        return $religion;
    }

    public static function index(array $params): Builder
    {
        return Religion::query()
            ->where('approved', true);
    }

    public static function show(Religion $religion): Religion
    {
        $religion->load([
            'doctrines' => fn($q) => $q->limit(10),
        ]);

        /** @var EloquentCollection<Religion> $descendants */
        $descendants = Cache::flexible('descendants.' . $religion->id, [600, 7200], function () use ($religion) {
            return Religion::withMaxDepth(Religion::DEFAULT_DEPTH, function () use ($religion) {
                return $religion->descendants->where('approved', true);
            });
        });

        $religion->setRelation('descendants', $descendants->take(10));

        return $religion;
    }

    public static function update(Religion $religion, array $params): Religion
    {
        $religion->forceFill($params)->save();

        return $religion;
    }

    public static function updateMany(array $params, EloquentCollection $religions): EloquentCollection
    {
        Religion::query()
            ->whereIn('id', $religions->pluck('id'))
            ->update($params['data']);

        $religions->each(function (Religion $religion) use ($params) {
            $religion->forceFill($params['data']);
        });

        return $religions;
    }

    public static function delete(EloquentCollection $religions): void
    {
        Religion::query()
            ->whereIn('id', $religions->pluck('id'))
            ->delete();
    }

    public static function canUpdateAll(EloquentCollection $religions, User $user): bool
    {
        return true;
    }

    public static function canDeleteAll(EloquentCollection $religions, User $user): bool
    {
        return true;
    }
}
