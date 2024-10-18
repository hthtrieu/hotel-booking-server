<?php

namespace App\Services\Invoice;

use App\ApiCode;
use App\Exceptions\ResponseException;
use App\Helpers\DayTimeHelper;
use App\Traits\ResponseApi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Repositories\Invoice\IInvoiceRepository;

class InvoiceService implements InvoiceServiceInterface
{
    use ResponseApi;

    public function __construct(
        private readonly IInvoiceRepository $invoiceRepo,
    ) {}

    public function updateInvoiceCancel(string $invoiceId, float $refund_amount){
        $invoiceUpdated = $this->invoiceRepo->find($invoiceId);
        if($invoiceUpdated){
            if($refund_amount>$invoiceUpdated->invoice_amount){
                throw new ResponseException("Not Enough for Refund");
            }
            return $this->invoiceRepo->update($invoiceUpdated->id, [
                'refund_amount'=>$refund_amount,
                'time_canceled'=>DayTimeHelper::getLocalDateTimeFormat(),
            ]);
        }
        return null;
    }
}
