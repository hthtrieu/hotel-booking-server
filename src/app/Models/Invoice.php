<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends BaseModel
{
    protected $fillable = [
        'invoice_amount',
        'refund_amount',
        'time_canceled',
        'time_created',
        'time_paid',
    ];
}
