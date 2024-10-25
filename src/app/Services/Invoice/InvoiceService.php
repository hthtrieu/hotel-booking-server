<?php

namespace App\Services\Invoice;

use App\ApiCode;
use App\Dtos\Payment\CreateRefundRequestDTO;
use App\Enums\ReservationStatusEnum;
use App\Exceptions\DataNotFoundException;
use App\Exceptions\ResponseException;
use App\Helpers\DayTimeHelper;
use App\Models\Invoice;
use App\Traits\ResponseApi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Repositories\Invoice\IInvoiceRepository;
use App\Repositories\Reservation\IReservationRepository;
use App\Services\Reservation\IReservationService;

class InvoiceService implements InvoiceServiceInterface
{
    use ResponseApi;

    public function __construct(
        private readonly IInvoiceRepository $invoiceRepo,
        private readonly IReservationRepository $reservationRepo,
        private readonly IReservationService $reservationService,
    ) {}

    public function updateInvoiceCancel(string $invoiceId, float $refund_amount)
    {
        $invoiceUpdated = $this->invoiceRepo->find($invoiceId);
        if ($invoiceUpdated) {
            if ($refund_amount > $invoiceUpdated->invoice_amount) {
                throw new ResponseException("Not Enough for Refund");
            }
            return $this->invoiceRepo->update($invoiceUpdated->id, [
                'refund_amount' => $refund_amount,
                'time_canceled' => DayTimeHelper::getLocalDateTimeFormat(),
            ]);
        }
        return null;
    }

    public function getInvoiceByOrderId(string $orderId)
    {
        return $this->invoiceRepo->findBy("order_id", $orderId);
    }

    public function checkValidRefundRequest(CreateRefundRequestDTO $data)
    {
        $invoice = $this->invoiceRepo->findBy("order_id", $data->order_id, ['reservation']);
        if (!$invoice) {
            throw new DataNotFoundException("Invoice not found");
        }
        if (($invoice->time_canceled && $invoice->refund_amount) || ($invoice->reservation->status === ReservationStatusEnum::REFUND->value)) {
            throw new ResponseException("Invoice refunded");
        }

        $localNow = DayTimeHelper::getLocalDateTimeFormat('Y-m-d H:i:s');
        $reservationStartEndDay = $this->reservationService->getReservationStartEndDayById($invoice->reservation->id);
        $startDay = DayTimeHelper::formatStringDateTime($reservationStartEndDay['start_day'], 'Y-m-d H:i:s');

        $daysUntilReservation = Carbon::parse($localNow)->diffInDays(Carbon::parse($startDay)) + 1;

        // Kiểm tra điều kiện hoàn tiền
        if ($daysUntilReservation > 1 && $data->transaction_type !== '02') {
            throw new ResponseException("Reservation should refund full");
        }

        if ($daysUntilReservation <= 1 && $data->transaction_type !== '03') {
            throw new ResponseException("Reservation should refund partial");
        }

        if ($data->transaction_type === '02') {
            if ($data->amount !== $invoice->invoice_amount) {
                throw new ResponseException("Should refund all invoice amount");
            }
        }
        if ($data->transaction_type === '03') {
            if ($data->amount !== ($invoice->invoice_amount * 0.1)) {
                throw new ResponseException("Should refund 10% invoice amount");
            }
        }

        return true;
    }
}
