<?php

namespace App\Validators;

use Closure;
use Illuminate\Validation\Rules\Password;

class UserValidator extends BaseValidator
{
    public static function rules(
        bool $update = false,
        bool $searching = false,
        ?Closure $merge = null
    ): array {
        [$required, $method] = self::getRequiredAndMethod($update, $searching);

        $password = Password::min(8)->mixedCase()->numbers();

        $rules = [
            'name' => [$required, 'string', 'max:50'],
            'username' => [$required, 'string', 'max:30', 'unique:users,username'],
            'email' => [$required, 'string', 'email', 'max:255', 'unique:users,email'],
            'gender' => [$required, 'string', 'in:male,female'],
            'faith_id' => ['sometimes', 'nullable', 'integer', 'exists:faiths,id'],
            'country_code' => ['sometimes', 'nullable', 'string', 'max:5'],
            'password' => [$required, 'confirmed', $password],
        ];

        return is_null($merge) ? $rules : $merge($rules);
    }
}
