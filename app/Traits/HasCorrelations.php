<?php

namespace App\Traits;

use App\Models\Correlation;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasCorrelations
{
    /**
     * Get correlations where this model is the source.
     */
    public function correlationsFrom(): MorphMany
    {
        return $this->morphMany(Correlation::class, 'correlatable_from');
    }

    /**
     * Get correlations where this model is the target.
     */
    public function correlationsTo(): MorphMany
    {
        return $this->morphMany(Correlation::class, 'correlatable_to');
    }

    /**
     * Get all correlations (both from and to).
     */
    public function correlations()
    {
        return $this->correlationsFrom->merge($this->correlationsTo);
    }
}
