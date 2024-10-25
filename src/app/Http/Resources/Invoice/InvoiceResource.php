<?php

namespace App\Http\Resources\Invoice;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "invoice_amount" => $this->invoice_amount,
            "refund_amount" => $this->refund_amount ? $this->refund_amount : "",
            "order_id" => $this->order_id,
            "time_canceled" => $this->time_canceled ? $this->time_canceled : "",
            "time_created" => $this->time_created ? $this->time_created : "",
            "time_paid" => $this->time_paid,
            "created_by" => $this->created_by ? $this->created_by : "",
        ];
    }
}
