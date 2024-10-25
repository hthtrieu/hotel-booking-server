<?php

namespace App\Dtos\Invoice;

use App\Models\Amenity;
use App\Models\RoomTypes;
use Illuminate\Database\Eloquent\Collection;

class InvoiceDTO
{
    public function __construct(
        public string $id,
        public ?float $invoice_amount,
        public ?float $refund_amount,
        public ?string $order_id,
        public ?string $time_canceled,
        public ?string $time_created,
        public ?string $time_paid,
        public ?string $created_by,
        //!todo: add payment type
    ) {}

    public static function fromModel($invoice)
    {
        return new self(
            $invoice->id ?? "",
            $invoice->invoice_amount ?? null,
            $invoice->refund_amount ?? null,
            $invoice->order_id ?? null,
            $invoice->time_canceled ?? null,
            $invoice->time_created ?? null,
            $invoice->time_paid ?? null,
            $invoice->created_by ?? null,
        );
    }
}
