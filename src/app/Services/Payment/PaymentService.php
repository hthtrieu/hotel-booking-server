<?php

namespace App\Services\Payment;

use App\Enums\ReservationStatusEnum;
use App\Helpers\DayTimeHelper;
use App\Helpers\VnPayHelper;
use App\Traits\ResponseApi;
use Carbon\Carbon;
use App\Repositories\Reservation\IReservationRepository;
use App\Services\Reservation\IReservationService;
use App\Http\Requests\Reservation\CreateReservationRequest;
use App\Repositories\Invoice\IInvoiceRepository;
use App\Repositories\User\UserRepositoryInterface;

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

    public function paymentSuccess($request)
    {
        // Tìm reservation theo ID
        $reservationUpdated = $this->reservationRepo->find($request->reservation_id);
        $reservationUpdated->reservation_code = $request->code;
        $reservationUpdated->status = ReservationStatusEnum::CONFIRMED->value;

        // Cập nhật reservation
        $this->reservationRepo->update($reservationUpdated->id, $reservationUpdated->toArray());

        // Lấy thông tin user từ reservation
        $user = $reservationUpdated->user; // Giả sử mối quan hệ user() đã được định nghĩa
        $user = $this->userRepo->findBy('email', $user->email);

        //!todo create invoice
        $invoice = $this->invoiceRepo->insertInvoice([
            'invoice_amount' => $reservationUpdated->total_price,
            'time_paid' => DayTimeHelper::convertStringToLocalDateTime($request->transaction_date),
            'order_id' => $request->order_id,
            'payment_method' => $request->payment_method,
            'reservation' => $reservationUpdated, // Lưu reservation ID
            'user' => $user, // Lưu user ID
        ]);

        //!todo send email invoice
        return $this->respondWithMessage('get successed');
    }
}
