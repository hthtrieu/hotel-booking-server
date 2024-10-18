<?php

namespace App\Services\Invoice;

interface InvoiceServiceInterface {

    public function updateInvoiceCancel(string $invoiceId, float $refund_amount);
}
