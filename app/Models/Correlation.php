<?php

namespace App\Models;

use App\Enums\CorrelationType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Correlation extends Model
{
    /** @use HasFactory<\Database\Factories\CorrelationFactory> */
    use HasFactory;

    protected $primaryKey = null;
    public $incrementing = false;

    public function casts()
    {
        return [
            'type' => CorrelationType::class,
        ];
    }

    /**
     * Get the source model of the correlation.
     */
    public function correlatableFrom(): MorphTo
    {
        return $this->morphTo('correlatable_from');
    }

    /**
     * Get the target model of the correlation.
     */
    public function correlatableTo(): MorphTo
    {
        return $this->morphTo('correlatable_to');
    }
}
