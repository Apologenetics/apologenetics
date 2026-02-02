<?php

namespace App\Http\Requests\Tag;

use App\Models\Tag;
use App\Services\TagService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class TagDeleteRequest extends FormRequest
{
    protected Collection $tags;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function passedValidation(): void
    {
        $tags = Tag::query()
            ->whereIn('id', $this->safe()->array('ids'))
            ->get();

        abort_unless(TagService::canDeleteAll(
            tags: $tags,
            user: Auth::user(),
        ), 403);

        $this->tags = $tags;
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
            'ids.*' => ['required', 'integer', 'exists:tags,id'],
        ];
    }

    public function tags(): Collection
    {
        return $this->tags;
    }
}
