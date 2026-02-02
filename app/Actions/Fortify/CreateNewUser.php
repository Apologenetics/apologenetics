<?php

namespace App\Actions\Fortify;

use App\Models\User;
use App\Services\UserService;
use App\Validators\FaithValidator;
use App\Validators\UserValidator;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Throwable;

class CreateNewUser implements CreatesNewUsers
{
    /**
     * Validate and create a newly registered user.
     *
     * @param array<string, string> $input
     * @throws Throwable
     */
    public function create(array $input): User
    {
        if (isset($input['religion_id']) && $input['religion_id'] == 0) {
            $input['religion_id'] = null;
        }

        $rules = array_merge(UserValidator::rules(merge: function ($rules) {
            return Arr::except($rules, ['faith_id', 'country_code']);
        }), FaithValidator::rules(merge: function ($rules) {
            return Arr::except($rules, ['user_id']);
        }));

        $validated = Validator::make($input, $rules)->validate();

        return UserService::create([
            'user' => Arr::only($validated, ['name', 'username', 'password', 'email', 'gender']),
            'faith' => Arr::only($validated, ['religion_id', 'date_converted', 'reason_converted']),
        ]);
    }
}
