<?php

namespace App\Services\Payment;

use App\Dtos\Reservation\CreateReservationRequestDTO;
use App\Dtos\Payment\CreatePaymentUrlDTO;
use App\Dtos\Payment\CreateRefundRequestDTO;
use App\Enums\ReservationStatusEnum;
use App\Exceptions\DataNotFoundException;
use App\Exceptions\ResponseException;
use App\Helpers\DayTimeHelper;
use App\Helpers\ReservationHelper;
use App\Helpers\VnPayHelper;
use App\Traits\ResponseApi;
use Carbon\Carbon;
use App\Repositories\Reservation\IReservationRepository;
use App\Services\Reservation\IReservationService;
use App\Http\Requests\Reservation\CreateReservationRequest;
use App\Mail\ReservationConfirmMail;
use App\Repositories\Invoice\IInvoiceRepository;
use App\Repositories\User\UserRepositoryInterface;
use App\Services\Invoice\InvoiceServiceInterface;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class PaymentService implements IPaymentService
{
    use ResponseApi;

    public function __construct(
        private readonly IReservationRepository $reservationRepo,
        private readonly IReservationService $reservationService,
        private readonly IInvoiceRepository $invoiceRepo,
        private readonly UserRepositoryInterface $userRepo,
        private readonly InvoiceServiceInterface $invoiceService,
    ) {}

    public function createPaymentRequest(CreateReservationRequestDTO $data, $request)
    {
        $validAmount = $this->reservationService->checkValidReservationAmount($data);
        if (!$validAmount) {
            throw new ResponseException("Vat not correct");
        }
        $urlData = $this->createVnpayURL(new CreatePaymentUrlDTO($data->vat, VnPayHelper::getIpRequest($request)));
        // //save reservation status is pending
        $reservation = $this->reservationService->createNewReservation(($data));
        if (isset($_POST['redirect'])) {
            header('Location: ' . $urlData['vnp_Url']);
            die();
        }
        //!warning: delete after test api
        $urlData['reservation_id'] = $reservation->id;
        return $urlData;
    }
    public function createVnpayURL(CreatePaymentUrlDTO $data)
    {
        $vnp_Url = config('vnpay.vnp_URL');
        $vnp_Returnurl = config('vnpay.vnp_Return_URL');
        $vnp_TmnCode = config('vnpay.vnp_TmnCode');
        $vnp_HashSecret = config('vnpay.vnp_HashSecret');
        // dd($vnp_Url, $vnp_Returnurl, $vnp_TmnCode, $vnp_HashSecret);

        $vnp_TxnRef = VnPayHelper::generateOrderId();

        $vnp_OrderInfo = "Thanh toan don dat: {$vnp_TxnRef}";
        $vnp_OrderType = 'other';
        $vnp_Amount = $data->amount * 100; //!todo: check amount is not decimal
        $vnp_Locale = "vn";
        // $vnp_BankCode = $_POST['bank_code'];
        $vnp_IpAddr = $data->requestIp;
        //Add Params of 2.0.1 Version
        $vnp_CreateDate = Carbon::now()->setTimezone('Asia/Ho_Chi_Minh')->format('YmdHis');
        $vnp_ExpireDate = Carbon::now()->setTimezone('Asia/Ho_Chi_Minh')->addMinutes(10)->format('YmdHis');

        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => $vnp_CreateDate,
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,
            "vnp_ExpireDate" => $vnp_ExpireDate,
        );

        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $vnp_Url . "?" . $query;
        if (isset($vnp_HashSecret)) {
            $vnpSecureHash =   hash_hmac('sha512', $hashdata, $vnp_HashSecret); //
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }
        $returnData = array(
            'vnp_url' => $vnp_Url,
            'order_id' => $vnp_TxnRef,
        );
        return $returnData;
    }
    public function refund(CreateRefundRequestDTO $request)
    {
        $isValid = $this->invoiceService->checkValidRefundRequest($request);
        if (!$isValid) {
            throw new ResponseException("Invalid Request");
        }
        $invoiced = $this->invoiceService->getInvoiceByOrderId($request->order_id);
        $vnp_Url = config('vnpay.vnp_REFUND_URL');
        $vnp_TmnCode = config('vnpay.vnp_TmnCode');
        $vnp_HashSecret = config('vnpay.vnp_HashSecret');
        $vnp_TxnRef = $request->order_id; // Lấy từ request
        $vnp_RequestId = VnPayHelper::generateRefundId();
        $vnp_OrderInfo = "Hoan tien don: {$vnp_TxnRef}";
        $vnp_Amount = $request->amount * 100; // Số tiền hoàn tiền, kiểm tra tính hợp lệ
        $vnp_IpAddr = '127.0.0.1'; // Lấy IP server
        $vnp_CreateDate = Carbon::now()->setTimezone('Asia/Ho_Chi_Minh')->format('YmdHis');
        $vnp_TransactionDate = $invoiced->transaction_date; // Lấy từ request? getinvoice -> ...

        $inputData = [
            "vnp_RequestId" => $vnp_RequestId,
            "vnp_Version" => '2.1.0',
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_TransactionType" => '02', // Đảm bảo đây là string
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => 'refund',
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_TxnRef" => $vnp_TxnRef,
            "vnp_CreateDate" => $vnp_CreateDate,
            "vnp_TransactionDate" => $vnp_TransactionDate,
            "vnp_CreateBy" => $request->userName, // Lấy từ request
        ];

        // Build the hashdata

        $hashdata = implode('|', [
            $inputData['vnp_RequestId'],
            $inputData['vnp_Version'],
            $inputData['vnp_Command'],
            $inputData['vnp_TmnCode'],
            $inputData['vnp_TransactionType'],
            $inputData['vnp_TxnRef'],
            $inputData['vnp_Amount'],
            isset($inputData['vnp_TransactionNo']) ? $inputData['vnp_TransactionNo'] : "",
            $inputData['vnp_TransactionDate'],
            $inputData['vnp_CreateBy'],
            $inputData['vnp_CreateDate'],
            $inputData['vnp_IpAddr'],
            $inputData['vnp_OrderInfo'],
        ]);

        $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
        $inputData['vnp_SecureHash'] = $vnpSecureHash;
        try {
            $client = new Client();
            $response = $client->post($vnp_Url, [
                'json' => $inputData,
                'headers' => [
                    'Content-Type' => 'Application/json'
                ],
            ]);
            $responseData = json_decode($response->getBody(), true);
            // dd($responseData);
            if ($responseData['vnp_ResponseCode'] == '00') {
                $invoice = $this->invoiceRepo->findBy('order_id', $inputData['vnp_TxnRef']);
                $this->invoiceService->updateInvoiceCancel($invoice->id, $request->amount);
                $reservation = $this->reservationRepo->update($invoice->reservation->id, [
                    'status' => ReservationStatusEnum::CANCELLED,
                ]);
                return $this->reservationService->getInvoiceByReservationId($reservation->id);
            } else {
                // ! in dev mode
                $invoice = $this->invoiceRepo->findBy('order_id', $inputData['vnp_TxnRef']);
                $this->invoiceService->updateInvoiceCancel($invoice->id, $request->amount);
                $reservation = $this->reservationRepo->update($invoice->reservation->id, [
                    'status' => ReservationStatusEnum::CANCELLED,
                ]);

                return $this->reservationService->getInvoiceByReservationId($reservation->id);
            }
        } catch (\Exception $e) {
            throw new ResponseException($e->getMessage());
        }
    }


    public function paymentSuccess($request)
    {
        return DB::transaction(function () use ($request) {
            // Kiểm tra xem hóa đơn đã tồn tại chưa và khóa hàng dữ liệu tương ứng
            $existedInvoice = $this->invoiceRepo->findByWithLock('order_id', $request->order_id);

            if ($existedInvoice) {
                return $this->reservationService->getInvoiceByReservationId($request->reservation_id);
            }

            // Tìm reservation theo ID và khóa hàng dữ liệu
            $reservationUpdated = $this->reservationRepo->findWithLock($request->reservation_id);
            if (!$reservationUpdated) {
                throw new DataNotFoundException("Reservation not found");
            }
            $reservationUpdated->reservation_code = ReservationHelper::generateReservationCode();
            $reservationUpdated->status = ReservationStatusEnum::CONFIRMED->value;

            // Cập nhật reservation
            $result = $this->reservationRepo->update($reservationUpdated->id, $reservationUpdated->toArray());

            // Lấy thông tin user từ reservation
            $user = $reservationUpdated->user;
            $user = $this->userRepo->findBy('email', $user->email);

            // Tạo hóa đơn
            $invoice = $this->invoiceRepo->insertInvoice([
                'invoice_amount' => $reservationUpdated->total_price,
                'time_paid' => DayTimeHelper::formatStringDateTime($request->transaction_date),
                'order_id' => $request->order_id,
                'payment_method' => $request->payment_method,
                'reservation' => $reservationUpdated,
                'user' => $user,
            ]);

            if ($invoice) {
                $data = $this->reservationService->getInvoiceByReservationId($reservationUpdated->id);
                Mail::to($user->email)->send(new ReservationConfirmMail($data));
                return $data;
            } else {
                return null;
            }
        });
    }
}
