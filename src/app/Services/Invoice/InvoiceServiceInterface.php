<?php

namespace App\Services\Invoice;

use App\Dtos\Payment\CreateRefundRequestDTO;

interface InvoiceServiceInterface
{

    public function updateInvoiceCancel(string $invoiceId, float $refund_amount);

    public function checkValidRefundRequest(CreateRefundRequestDTO $data);

    public function getInvoiceByOrderId(string $orderId);
}
