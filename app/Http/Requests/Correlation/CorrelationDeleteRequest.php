<?php

namespace App\Http\Requests\Correlation;

use App\Models\Correlation;
use App\Services\CorrelationService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CorrelationDeleteRequest extends FormRequest
{
    protected Collection $correlations;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function passedValidation(): void
    {
        $correlations = Correlation::query()
            ->where(function ($query) {
                foreach ($this->validated('ids') as $id) {
                    $query->orWhere(function ($q) use ($id) {
                        $q->where('correlatable_from_type', $id['correlatable_from_type'])
                          ->where('correlatable_from_id', $id['correlatable_from_id'])
                          ->where('correlatable_to_type', $id['correlatable_to_type'])
                          ->where('correlatable_to_id', $id['correlatable_to_id']);
                    });
                }
            })
            ->get();

        abort_unless(CorrelationService::canDeleteAll(
            correlations: $correlations,
            user: Auth::user(),
        ), 403);

        $this->correlations = $correlations;
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
            'ids.*' => ['required', 'array'],
            'ids.*.correlatable_from_type' => ['required', 'string'],
            'ids.*.correlatable_from_id' => ['required', 'integer'],
            'ids.*.correlatable_to_type' => ['required', 'string'],
            'ids.*.correlatable_to_id' => ['required', 'integer'],
        ];
    }

    public function correlations(): Collection
    {
        return $this->correlations;
    }
}
