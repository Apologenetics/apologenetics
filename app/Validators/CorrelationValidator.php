<?php

namespace App\Validators;

use App\Enums\CorrelationType;
use Closure;
use Illuminate\Validation\Rule;

class CorrelationValidator extends BaseValidator
{
    public static function rules(
        bool $update = false,
        bool $searching = false,
        Closure|null $merge = null,
    ): array {
        [$required, $method] = self::getRequiredAndMethod($update, $searching);

        $rules = [
            'correlatable_from_type' => [$required, 'string', 'max:255'],
            'correlatable_from_id' => [$required, 'integer'],
            'correlatable_to_type' => [$required, 'string', 'max:255'],
            'correlatable_to_id' => [$required, 'integer'],
            'description' => ['nullable', 'string'],
            'type' => [$required, 'integer', Rule::in(CorrelationType::cases())],
        ];

        return is_null($merge) ? $rules : $merge($rules);
    }
}
