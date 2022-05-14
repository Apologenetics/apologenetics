<?php

namespace App\Http\Livewire\Doctrines;

use Livewire\Component;
use App\Models\Religion;
use App\Models\Denomination;
use App\Traits\ConvertEmptyArrayStrings;
use App\Contracts\Doctrine\CreatesDoctrine;
use Illuminate\Database\Eloquent\Collection;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Create extends Component
{
    use ConvertEmptyArrayStrings;

    public array $state = [];

    public ?string $type;

    public ?int $typeId;

    public Collection $religions;

    public ?Collection $denominations = null;

    public Religion|Denomination $entity;

    /**
     * Constructor for Livewire component
     *
     * @param ?string $type
     * @param ?int $typeId
     * @throws NotFoundHttpException
     * @return void
     */
    public function mount(?int $religionId = null, ?int $denominationId = null)
    {
        if (isset($religionId)) {
            $this->entity = Religion::query()->find($religionId);
        } else if (isset($denominationId)) {
            $this->entity = Denomination::query()->find($denominationId);
        }

        $hasId = isset($religionId, $denominationId);

        $this->religions = Religion::all();

        $religionId ??= isset($denominationId) ?
            $this->entity->religion_id :
            $this->religions->first()->getKey();

        if (!$hasId) {
            $this->entity = Religion::query()->find($religionId);
        }

        $this->denominations = Denomination::query()
            ->where('religion_id', $religionId)
            ->where('approved', true)
            ->get();

        $this->state = ['religion_id' => $religionId, 'denomination_id' => $denominationId ?? 0];
    }

    public function submit(CreatesDoctrine $createDoctrine)
    {
        $this->state['created_by'] = auth()->id();

        $createDoctrine(
            $this->convertEmptyArrayStrings($this->state)
        );
    }

    public function updatedStateReligionId()
    {
        $this->denominations = Denomination::query()
            ->where('religion_id', $this->state['religion_id'])
            ->get();
    }

    public function render()
    {
        return view('livewire.doctrines.create');
    }
}