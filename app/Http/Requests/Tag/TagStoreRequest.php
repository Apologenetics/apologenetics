<?php

namespace App\Http\Requests\Tag;

use App\Models\Tag;
use App\Validators\TagValidator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class TagStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('create', Tag::class);
    }

    protected function prepareForValidation(): void
    {
        // Normalize the string name
        if ($this->has('name')) {
            $this->merge([
                'name' => trim(strtolower($this->string('name')))
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return TagValidator::rules();
    }
}
