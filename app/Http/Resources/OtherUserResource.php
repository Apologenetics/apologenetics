<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OtherUserResource extends JsonResource
{
    public $resource = User::class;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'username' => $this->resource->username,
            'doctrines' => $this->whenLoaded(
                'doctrines',
                $this->resource->doctrines,
            ),
            'correlations' => $this->whenLoaded(
                'correlations',
                $this->resource->doctrines,
            ),
        ];
    }
}
