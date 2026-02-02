<?php

namespace App\Models;

use App\Traits\HasCorrelations;
use App\Traits\HasDoctrines;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Faith extends Model
{
    /** @use HasFactory<\Database\Factories\FaithFactory> */
    use HasFactory;
    use HasDoctrines;
    use HasCorrelations;

    public function casts(): array
    {
        return [
            'religion_id' => 'integer',
            'user_id' => 'integer',
            'date_converted' => 'date:Y-m-d',
            'date_reverted' => 'date:Y-m-d',
        ];
    }

    public function religion(): BelongsTo
    {
        return $this->belongsTo(Religion::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
