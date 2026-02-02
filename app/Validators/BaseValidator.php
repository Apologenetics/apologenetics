<?php

namespace App\Validators;

use Closure;
use App\Contracts\ValidatesModel;
use App\Traits\ModelValidator;
use Illuminate\Contracts\Validation\Validator;

abstract class BaseValidator implements ValidatesModel
{
    use ModelValidator;

    public abstract static function rules(
        bool $update = false,
        bool $searching = false,
        Closure|null $merge = null,
    ): array;

    public static function getRequiredAndMethod(
        bool $update,
        bool $searching,
    ): array {
        $required = $update ? 'sometimes' : 'required';
        $method = $update && !$searching ? 'updated' : 'created';

        return [$required, $method];
    }

    public static function appendKeyToModelRules(
        string $key,
        bool $update = false,
        bool $searching = false,
        array $rules = [],
        Closure|null $merge = null,
    ): array {
        $modelRules = static::rules($update, $searching, $merge);

        foreach ($modelRules as $field => $fieldRules) {
            $rules[$key . '.' . $field] = $fieldRules;
        }

        return $rules;
    }
}
