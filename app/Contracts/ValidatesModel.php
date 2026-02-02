<?php

namespace App\Contracts;

use Closure;
use Illuminate\Contracts\Validation\Validator;

interface ValidatesModel
{
    public static function rules(
        bool $update = false,
        bool $searching = false,
        Closure|null $merge = null,
    ): array;

    public static function validator(
        array $data,
        bool $update = false,
        bool $searching = false,
        Closure|null $merge = null,
    ): Validator;
}
