<?php

namespace App\Models;

use App\Traits\HasCorrelations;
use App\Traits\HasDoctrines;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;

class Religion extends Model
{
    /** @use HasFactory<\Database\Factories\ReligionFactory> */
    use HasFactory, HasDoctrines, HasCorrelations, HasRecursiveRelationships;

    const DEFAULT_DEPTH = 3;

    public function faiths(): HasMany
    {
        return $this->hasMany(Faith::class);
    }
}
