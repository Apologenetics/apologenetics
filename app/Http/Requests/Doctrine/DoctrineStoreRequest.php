<?php

namespace App\Http\Requests\Doctrine;

use App\Models\Doctrine;
use App\Validators\DoctrineValidator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class DoctrineStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('create', Doctrine::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return DoctrineValidator::rules(merge: function (array $rules) {
            return array_merge($rules, [
                'religion_id' => ['required', 'exists:religions,id'],
            ]);
        });
    }
}
