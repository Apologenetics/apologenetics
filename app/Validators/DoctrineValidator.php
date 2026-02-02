<?php

namespace App\Validators;

use Closure;

class DoctrineValidator extends BaseValidator
{
    public static function rules(
        bool $update = false,
        bool $searching = false,
        Closure|null $merge = null,
    ): array {
        [$required, $method] = self::getRequiredAndMethod($update, $searching);

        $rules = [
            'title' => [$required, 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:10000'],
        ];

        return is_null($merge) ? $rules : $merge($rules);
    }
}
