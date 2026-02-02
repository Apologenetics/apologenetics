<?php

namespace App\Http\Requests\Religion;

use App\Models\Religion;
use App\Validators\ReligionValidator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class ReligionStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('create', Religion::class);
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'approved' => false,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return ReligionValidator::rules();
    }
}
