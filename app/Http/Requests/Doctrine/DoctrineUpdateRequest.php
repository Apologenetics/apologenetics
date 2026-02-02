<?php

namespace App\Http\Requests\Doctrine;

use App\Models\Doctrine;
use App\Services\DoctrineService;
use App\Validators\DoctrineValidator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class DoctrineUpdateRequest extends FormRequest
{
    protected Collection $doctrines;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function passedValidation(): void
    {
        $doctrines = Doctrine::query()
            ->whereIn('id', $this->safe()->array('ids'))
            ->get();

        abort_unless(DoctrineService::canUpdateAll(
            doctrines: $doctrines,
            user: Auth::user(),
        ), 403);

        $this->doctrines = $doctrines;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return DoctrineValidator::appendKeyToModelRules(
            key: 'data',
            update: true,
            rules: [
                'data' => ['required', 'array'],
                'ids' => ['required', 'array'],
                'ids.*' => ['required', 'integer', 'exists:doctrines,id'],
            ]
        );
    }

    public function doctrines(): Collection
    {
        return $this->doctrines;
    }
}
