<?php

namespace App\Repositories\Invoice;

use App\Helpers\DayTimeHelper;
use App\Models\Invoice;
use App\Models\PaymentTypes;
use App\Models\Reservation;
use App\Models\Room;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class InvoiceRepository extends BaseRepository implements IInvoiceRepository
{

    protected $modelName = Invoice::class;

    public function findByWithLock($column, $value)
    {
        return Invoice::where($column, $value)->lockForUpdate()->first();
    }

    public function insertInvoice($data)
    {
        return DB::transaction(function () use ($data) {
            $paymentMethod = $data['payment_method'];
            $method = PaymentTypes::where('name', $paymentMethod)->first();

            if (!$method) {
                $newMethod = new PaymentTypes();
                $newMethod->name = $paymentMethod;
                $newMethod->save();
                $method = $newMethod;
            }

            $user = $data['user'];
            $reservation = $data['reservation'];
            $newInvoice = new Invoice();
            $newInvoice->invoice_amount = $data['invoice_amount'];
            $newInvoice->order_id = $data['order_id'];
            $newInvoice->time_created = DayTimeHelper::getLocalDateTimeFormat();
            $newInvoice->time_paid = $data['time_paid'];
            $newInvoice->created_by = 'user' . rand(1, 10);
            $newInvoice->user()->associate($user);
            $newInvoice->paymentTypes()->associate($method);
            $newInvoice->reservation()->associate($reservation);
            $newInvoice->save();

            return $newInvoice;
        });
    }
}
