<?php

namespace App\Services;

use App\Models\User;
use App\Models\Vote;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

class VoteService
{
    public static function store(array $params): Vote
    {
        $vote = new Vote();
        $vote->forceFill($params)->save();

        return $vote;
    }

    public static function index(array $params): Builder
    {
        return Vote::query();
    }

    public static function update(array $params, EloquentCollection $votes): EloquentCollection
    {
        foreach ($votes as $vote) {
            $vote->forceFill($params['data'])->save();
        }

        return $votes;
    }

    public static function delete(EloquentCollection $votes): void
    {
        foreach ($votes as $vote) {
            Vote::query()
                ->where('votable_type', $vote->votable_type)
                ->where('votable_id', $vote->votable_id)
                ->where('user_id', $vote->user_id)
                ->delete();
        }
    }

    public static function canUpdateAll(EloquentCollection $votes, User $user): bool
    {
        return true;
    }

    public static function canDeleteAll(EloquentCollection $votes, User $user): bool
    {
        return true;
    }
}
