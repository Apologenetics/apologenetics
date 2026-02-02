<?php

namespace App\Validators;

use Closure;

class ReligionValidator extends BaseValidator
{
    public static function rules(
        bool $update = false,
        bool $searching = false,
        Closure|null $merge = null,
    ): array {
        [$required, $method] = self::getRequiredAndMethod($update, $searching);

        $rules = [
            'name' => [$required, 'string', 'max:50'],
            'description' => ['nullable', 'string', 'max:255'],
            'approved' => [$required, 'boolean'],
            'parent_id' => ['nullable', 'exists:religions,id'],
        ];

        return is_null($merge) ? $rules : $merge($rules);
    }
}
