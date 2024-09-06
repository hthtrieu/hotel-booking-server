<?php

namespace App\Models;

use App\Enums\ReservatioinStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Reservation extends BaseModel
{
    protected $fillable = [
        'email',
        'site_fees',
        'tax_paid',
        'status',
        'total_price',
    ];
    protected $casts = [
        'password' => 'hashed',
        'role' => ReservatioinStatusEnum::class,
    ];
}
