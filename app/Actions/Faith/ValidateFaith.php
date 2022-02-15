<?php

namespace App\Actions\Faith;

use App\Contracts\Faith\ValidatesFaith;
use Illuminate\Contracts\Validation\Validator;

final class ValidateFaith implements ValidatesFaith
{
    public function __invoke(array $data, $hasDenomination = false): Validator
    {
        $rules = [
            'religion_id' => ['required', 'integer'],
            'start_of_faith' => ['required', 'date'],
            'user_id' => ['required', 'integer'],
            'note' => ['string', 'max:255'],
        ];

        $messages = [
            'religion_id.required' => 'Religion is required',
            'religion_id.integer' => 'Religion must be an ID',
            'start_of_faith.required' => 'Must have a beginning of faith',
            'start_of_faith.date' => 'Start of faith must be a date',
            'user_id.required' => 'User ID is required',
            'user_id.integer' => 'User ID must be an ID',
            'note.string' => 'Note must be a string',
            'note.max' => 'Max length of 255 characters exceeded',
        ];

        if ($hasDenomination) {
            $rules['denomination_id'] = ['required', 'integer'];
            $messages['denomination_id.required'] = 'Denomination is required for religion';
            $messages['denomination_id.integer'] = 'Denomination must be an ID';
        }

        return validator(
            $data,
            $rules,
            $messages
        );
    }
}