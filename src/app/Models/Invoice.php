<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Invoice extends BaseModel
{
    protected $fillable = [
        'invoice_amount',
        'refund_amount',
        'order_id',
        'time_canceled',
        'time_created',
        'time_paid',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function paymentTypes(): BelongsTo
    {
        return $this->belongsTo(PaymentTypes::class, 'payment_types_id');
    }

    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class, 'reservation_id');
    }
}
