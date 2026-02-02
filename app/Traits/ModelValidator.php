<?php

namespace App\Traits;

use Closure;
use Illuminate\Contracts\Validation\Validator;

trait ModelValidator
{
    public static function validator(
        array $data,
        bool $update = false,
        bool $searching = false,
        Closure|null $merge = null,
    ): Validator {
        return validator(
            $data,
            static::rules($update, $searching, $merge)
        );
    }
}
