<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reviews extends BaseModel
{
    protected $fullable = [
        'rating',
        'comment',
    ];

    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotels::class, 'hotel_id');
    }
}
