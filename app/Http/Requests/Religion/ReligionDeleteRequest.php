<?php

namespace App\Http\Requests\Religion;

use App\Models\Religion;
use App\Services\ReligionService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ReligionDeleteRequest extends FormRequest
{
    protected Collection $religions;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function passedValidation(): void
    {
        $religions = Religion::query()
            ->whereIn('id', $this->safe()->array('ids'))
            ->get();

        abort_unless(ReligionService::canDeleteAll(
            religions: $religions,
            user: Auth::user(),
        ), 403);

        $this->religions = $religions;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'ids' => ['required', 'array'],
            'ids.*' => ['required', 'integer', 'exists:religions,id'],
        ];
    }

    public function religions(): Collection
    {
        return $this->religions;
    }
}
