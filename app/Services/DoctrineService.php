<?php

namespace App\Services;

use App\Models\Doctrinable;
use App\Models\Doctrine;
use App\Models\Religion;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class DoctrineService
{
    /**
     * @throws \Throwable
     */
    public static function store(array $params): Doctrine
    {
        $doctrineParams = Arr::except($params, ['religion_id']);

        DB::beginTransaction();

        $doctrine = new Doctrine();
        $doctrine->forceFill($doctrineParams)->save();

        $doctrinable = new Doctrinable();
        $doctrinable->forceFill([
            'doctrinable_id' => $params['religion_id'],
            'doctrinable_type' => Religion::class,
            'doctrine_id' => $doctrine->id,
        ])->save();

        DB::commit();

        return $doctrine;
    }

    public static function index(array $params): Builder
    {
        return Doctrine::query();
    }

    public static function update(array $params, EloquentCollection $doctrines): EloquentCollection
    {
        Doctrine::query()
            ->whereIn('id', $doctrines->pluck('id'))
            ->update($params['data']);

        $doctrines->each(function (Doctrine $doctrine) use ($params) {
            $doctrine->forceFill($params['data']);
        });

        return $doctrines;
    }

    public static function delete(EloquentCollection $doctrines): void
    {
        Doctrine::query()
            ->whereIn('id', $doctrines->pluck('id'))
            ->delete();
    }

    public static function canUpdateAll(EloquentCollection $doctrines, User $user): bool
    {
        return true;
    }

    public static function canDeleteAll(EloquentCollection $doctrines, User $user): bool
    {
        return true;
    }
}
