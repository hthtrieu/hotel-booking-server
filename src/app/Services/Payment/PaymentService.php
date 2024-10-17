<?php

namespace App\Services\Payment;

use App\Enums\ReservationStatusEnum;
use App\Helpers\DayTimeHelper;
use App\Helpers\ReservationHelper;
use App\Helpers\VnPayHelper;
use App\Traits\ResponseApi;
use Carbon\Carbon;
use App\Repositories\Reservation\IReservationRepository;
use App\Services\Reservation\IReservationService;
use App\Http\Requests\Reservation\CreateReservationRequest;
use App\Repositories\Invoice\IInvoiceRepository;
use App\Repositories\User\UserRepositoryInterface;
use GuzzleHttp\Client;

class PaymentService implements IPaymentService
{
    use ResponseApi;

    public function __construct(
        private readonly IReservationRepository $reservationRepo,
        private readonly IReservationService $reservationService,
        private readonly IInvoiceRepository $invoiceRepo,
        private readonly UserRepositoryInterface $userRepo,
    ) {}

    public function createPaymentRequest(CreateReservationRequest $request)
    {
        $vnp_Url = config('vnpay.vnp_URL');
        $vnp_Returnurl = config('vnpay.vnp_Return_URL');
        $vnp_TmnCode = config('vnpay.vnp_TmnCode');
        $vnp_HashSecret = config('vnpay.vnp_HashSecret');
        // dd($vnp_Url, $vnp_Returnurl, $vnp_TmnCode, $vnp_HashSecret);

        $vnp_TxnRef = VnPayHelper::generateOrderId();

        $vnp_OrderInfo = "Thanh toan don dat: {$vnp_TxnRef}";
        $vnp_OrderType = 'other';
        $vnp_Amount = $request->totalPrice * 100; //!todo: check amount is not decimal
        $vnp_Locale = "vn";
        // $vnp_BankCode = $_POST['bank_code'];
        $vnp_IpAddr = VnPayHelper::getIpRequest($request);
        //Add Params of 2.0.1 Version
        $vnp_CreateDate = Carbon::now()->setTimezone('Asia/Ho_Chi_Minh')->format('YmdHis');
        $vnp_ExpireDate = Carbon::now()->setTimezone('Asia/Ho_Chi_Minh')->addMinutes(10)->format('YmdHis');

        // //Billing
        // $vnp_Bill_Mobile = $_POST['txt_billing_mobile'];
        // $vnp_Bill_Email = $_POST['txt_billing_email'];
        // $fullName = trim($_POST['txt_billing_fullname']);
        // if (isset($fullName) && trim($fullName) != '') {
        //     $name = explode(' ', $fullName);
        //     $vnp_Bill_FirstName = array_shift($name);
        //     $vnp_Bill_LastName = array_pop($name);
        // }
        // $vnp_Bill_Address = $_POST['txt_inv_addr1'];
        // $vnp_Bill_City = $_POST['txt_bill_city'];
        // $vnp_Bill_Country = $_POST['txt_bill_country'];
        // $vnp_Bill_State = $_POST['txt_bill_state'];

        // // Invoice
        // $vnp_Inv_Phone = $_POST['txt_inv_mobile'];
        // $vnp_Inv_Email = $_POST['txt_inv_email'];
        // $vnp_Inv_Customer = $_POST['txt_inv_customer'];
        // $vnp_Inv_Address = $_POST['txt_inv_addr1'];
        // $vnp_Inv_Company = $_POST['txt_inv_company'];
        // $vnp_Inv_Taxcode = $_POST['txt_inv_taxcode'];
        // $vnp_Inv_Type = $_POST['cbo_inv_type'];

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

            // "vnp_Bill_Mobile" => $vnp_Bill_Mobile,
            // "vnp_Bill_Email" => $vnp_Bill_Email,
            // "vnp_Bill_FirstName" => $vnp_Bill_FirstName,
            // "vnp_Bill_LastName" => $vnp_Bill_LastName,
            // "vnp_Bill_Address" => $vnp_Bill_Address,
            // "vnp_Bill_City" => $vnp_Bill_City,
            // "vnp_Bill_Country" => $vnp_Bill_Country,

            // "vnp_Inv_Phone" => $vnp_Inv_Phone,
            // "vnp_Inv_Email" => $vnp_Inv_Email,
            // "vnp_Inv_Customer" => $vnp_Inv_Customer,
            // "vnp_Inv_Address" => $vnp_Inv_Address,
            // "vnp_Inv_Company" => $vnp_Inv_Company,
            // "vnp_Inv_Taxcode" => $vnp_Inv_Taxcode,
            // "vnp_Inv_Type" => $vnp_Inv_Type
        );

        // if (isset($vnp_BankCode) && $vnp_BankCode != "") {
        //     $inputData['vnp_BankCode'] = $vnp_BankCode;
        // }
        // if (isset($vnp_Bill_State) && $vnp_Bill_State != "") {
        //     $inputData['vnp_Bill_State'] = $vnp_Bill_State;
        // }

        // var_dump($inputData);
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
            'code' => '00',
            'vnp_url' => $vnp_Url
        );
        //save reservation status is pending
        $reservation = $this->reservationService->createNewReservation(($request));
        if (isset($_POST['redirect'])) {
            header('Location: ' . $vnp_Url);
            die();
        }
        //!warning: delete after test api
        $returnData['order_id'] = $vnp_TxnRef;
        $returnData['reservation_id'] = $reservation->id;
        return $this->respond($returnData, "Payment Request");
    }

    public function refund($request)
    {
        $vnp_Url = config('vnpay.vnp_REFUND_URL');
        $vnp_TmnCode = config('vnpay.vnp_TmnCode');
        $vnp_HashSecret = config('vnpay.vnp_HashSecret');
        $vnp_TxnRef = $request->order_id; // Lấy từ request
        $vnp_RequestId = VnPayHelper::generateRefundId();
        $vnp_OrderInfo = "Hoan tien don: {$vnp_TxnRef}";
        $vnp_Amount = $request->price * 100; // Số tiền hoàn tiền, kiểm tra tính hợp lệ
        $vnp_IpAddr = VnPayHelper::getIpRequest($request); // Lấy IP server
        $vnp_CreateDate = Carbon::now()->setTimezone('Asia/Ho_Chi_Minh')->format('YmdHis');
        $vnp_TransactionDate = $request->transaction_date; // Lấy từ request

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
            "vnp_CreateBy" => 'user', // Lấy từ request
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
                return $this->respondWithMessage("Refund successful");
            } else {
                // return $this->respond(json_decode($response->getBody(), true));
                return $this->respondWithErrorMessage('Refund failed: ' . $responseData['vnp_Message']);
            }
        } catch (\Exception $e) {
            dd($e);
            return $this->respondWithErrorMessage($e->getMessage());
        }
    }


    public function paymentSuccess($request)
    {
        $existedInvoice = $this->invoiceRepo->findBy("order_id", $request->order_id);
        if ($existedInvoice) {
            return $this->reservationService->getInvoiceByReservationId($request->reservation_id);
        }
        // Tìm reservation theo ID
        $reservationUpdated = $this->reservationRepo->find($request->reservation_id);
        $reservationUpdated->reservation_code = ReservationHelper::generateReservationCode();
        $reservationUpdated->status = ReservationStatusEnum::CONFIRMED->value;

        // Cập nhật reservation
        $this->reservationRepo->update($reservationUpdated->id, $reservationUpdated->toArray());

        // Lấy thông tin user từ reservation
        $user = $reservationUpdated->user; // Giả sử mối quan hệ user() đã được định nghĩa
        $user = $this->userRepo->findBy('email', $user->email);

        //!todo create invoice
        $invoice = $this->invoiceRepo->insertInvoice([
            'invoice_amount' => $reservationUpdated->total_price,
            'time_paid' => DayTimeHelper::convertStringToDateTime($request->transaction_date),
            'order_id' => $request->order_id,
            'payment_method' => $request->payment_method,
            'reservation' => $reservationUpdated, // Lưu reservation ID
            'user' => $user, // Lưu user ID
        ]);
        if ($invoice) {
            return $this->reservationService->getInvoiceByReservationId($reservationUpdated->id);
        } else {
            return null;
        }
    }
}
