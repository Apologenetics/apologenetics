<?php

namespace App\Traits;

use App\Models\Doctrine;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait HasDoctrines
{
    /**
     * Get all doctrines for this model.
     */
    public function doctrines(): MorphToMany
    {
        return $this->morphToMany(Doctrine::class, 'doctrinable');
    }
}
