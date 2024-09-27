<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PaymentTypes extends BaseModel
{
    protected $fillable = [
        'name',
    ];

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class, 'payment_types_id');
    }
}
