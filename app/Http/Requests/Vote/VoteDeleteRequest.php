<?php

namespace App\Http\Requests\Vote;

use App\Models\Vote;
use App\Services\VoteService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class VoteDeleteRequest extends FormRequest
{
    protected Collection $votes;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function passedValidation(): void
    {
        $votes = Vote::query()
            ->where(function ($query) {
                foreach ($this->validated('ids') as $id) {
                    $query->orWhere(function ($q) use ($id) {
                        $q->where('user_id', $id['user_id'])
                          ->where('votable_type', $id['votable_type'])
                          ->where('votable_id', $id['votable_id']);
                    });
                }
            })
            ->get();

        abort_unless(VoteService::canDeleteAll(
            votes: $votes,
            user: Auth::user(),
        ), 403);

        $this->votes = $votes;
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
            'ids.*.user_id' => ['required', 'integer', 'exists:users,id'],
            'ids.*.votable_type' => ['required', 'string'],
            'ids.*.votable_id' => ['required', 'integer'],
        ];
    }

    public function votes(): Collection
    {
        return $this->votes;
    }
}
