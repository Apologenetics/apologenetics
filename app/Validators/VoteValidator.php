<?php

namespace App\Validators;

use Closure;

class VoteValidator extends BaseValidator
{
    public static function rules(
        bool $update = false,
        bool $searching = false,
        Closure|null $merge = null,
    ): array {
        [$required, $method] = self::getRequiredAndMethod($update, $searching);

        $rules = [
            'amount' => [$required, 'integer'],
            'user_id' => [$required, 'integer', 'exists:users,id'],
            'votable_type' => [$required, 'string', 'max:255'],
            'votable_id' => [$required, 'integer'],
        ];

        return is_null($merge) ? $rules : $merge($rules);
    }
}
