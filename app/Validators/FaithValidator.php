<?php

namespace App\Validators;

use Closure;

class FaithValidator extends BaseValidator
{
    public static function rules(
        bool $update = false,
        bool $searching = false,
        Closure|null $merge = null,
    ): array {
        [$required, $method] = self::getRequiredAndMethod($update, $searching);

        $rules = [
            'religion_id' => ['nullable', 'integer', 'exists:religions,id'],
            'user_id' => [$required, 'integer', 'exists:users,id'],
            'date_converted' => [$required, 'date'],
            'reason_converted' => ['nullable', 'string'],
            'date_reverted' => ['nullable', 'date'],
            'reason_reverted' => ['nullable', 'string'],
        ];

        return is_null($merge) ? $rules : $merge($rules);
    }
}
