<?php

namespace App\Services;

use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

class TagService
{
    public static function store(array $params): Tag
    {
        $tag = new Tag();
        $tag->forceFill($params)->save();

        return $tag;
    }

    public static function index(array $params): Builder
    {
        return Tag::query();
    }

    public static function update(array $params, EloquentCollection $tags): EloquentCollection
    {
        Tag::query()
            ->whereIn('id', $tags->pluck('id'))
            ->update($params['data']);

        $tags->each(function (Tag $tag) use ($params) {
            $tag->forceFill($params['data']);
        });

        return $tags;
    }

    public static function delete(EloquentCollection $tags): void
    {
        Tag::query()
            ->whereIn('id', $tags->pluck('id'))
            ->delete();
    }

    public static function canUpdateAll(EloquentCollection $tags, User $user): bool
    {
        return true;
    }

    public static function canDeleteAll(EloquentCollection $tags, User $user): bool
    {
        return true;
    }
}
