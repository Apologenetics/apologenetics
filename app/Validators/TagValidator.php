<?php

namespace App\Validators;

use Closure;

class TagValidator extends BaseValidator
{
    public static function rules(
        bool $update = false,
        bool $searching = false,
        Closure|null $merge = null,
    ): array {
        [$required, $method] = self::getRequiredAndMethod($update, $searching);

        $rules = [
            'name' => [$required, 'string', 'max:255', 'unique:tags,name'],
        ];

        return is_null($merge) ? $rules : $merge($rules);
    }
}
