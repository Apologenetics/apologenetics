<?php

namespace App\Services;

use App\Models\Faith;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Throwable;

class UserService
{
    /**
     * @throws Throwable
     */
    public static function create(array $params): User
    {
        return DB::transaction(function () use ($params) {
            $user = new User();
            $user->forceFill($params['user'])->save();

            $faith = new Faith();
            $faith->forceFill(array_merge($params['faith'], [
                'user_id' => $user->id,
            ]))->save();

            $user->forceFill(['faith_id' => $faith->id])->save();

            $user->setRelation('faith', $faith);
            return $user;
        });
    }

    public static function show(User $user)
    {
        // TODO: User settings for what to show
        $limit = function ($query) {
            $query->limit(5);
        };

        $user->load([
            'doctrines' => $limit,
            'correlations' => $limit,
        ]);

        return $user;
    }
}
