<?php

namespace App\Models;

use App\Traits\HasCorrelations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Doctrine extends Model
{
    /** @use HasFactory<\Database\Factories\DoctrineFactory> */
    use HasFactory;
    use HasCorrelations;

    /**
     * Get all models that have this doctrine.
     */
    public function doctrinables(): MorphToMany
    {
        return $this->morphedByMany(Religion::class, 'doctrinable')
            ->orWhere(function ($query) {
                $query->morphedByMany(Faith::class, 'doctrinable');
            });
    }
}
