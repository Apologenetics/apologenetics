<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    /** @use HasFactory<\Database\Factories\VoteFactory> */
    use HasFactory;

    protected $primaryKey = null;
    public $incrementing = false;

    public function casts(): array
    {
        return [
            'votable_id' => 'integer',
            'user_id' => 'integer',
            'amount' => 'integer',
        ];
    }
}
