<?php

namespace App\Http\Requests\Faith;

use App\Models\Faith;
use App\Services\FaithService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class FaithDeleteRequest extends FormRequest
{
    protected Collection $faiths;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function passedValidation(): void
    {
        $faiths = Faith::query()
            ->whereIn('id', $this->safe()->array('ids'))
            ->get();

        abort_unless(FaithService::canDeleteAll(
            faiths: $faiths,
            user: Auth::user(),
        ), 403);

        $this->faiths = $faiths;
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
            'ids.*' => ['required', 'integer', 'exists:faiths,id'],
        ];
    }

    public function faiths(): Collection
    {
        return $this->faiths;
    }
}
