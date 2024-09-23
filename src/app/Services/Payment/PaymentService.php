<?php

namespace App\Services\Payment;

use App\Helpers\VnPayHelper;
use App\Traits\ResponseApi;
use App\Http\Requests\Payment\CreatePaymentRequest;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use App\Repositories\Reservation\IReservationRepository;
use App\Services\Reservation\IReservationService;
use App\Http\Requests\Reservation\CreateReservationRequest;

class PaymentService implements IPaymentService
{
    use ResponseApi;

    public function __construct(
        private readonly IReservationRepository $reservationRepo,
        private readonly IReservationService $reservationService,
    ) {}

    public function createPaymentRequest(CreateReservationRequest $request)
    {
        $vnp_Url = config('vnpay.vnp_URL');
        $vnp_Returnurl = config('vnpay.vnp_RETURN_URL');
        $vnp_TmnCode = config('vnpay.vnp_TmnCode'); //Mã website tại VNPAY
        $vnp_HashSecret = config('vnpay.vnp_HashSecret'); //Chuỗi bí mật

        $vnp_TxnRef = VnPayHelper::generateOrderId(); // create random for identify the order
        $vnp_OrderInfo = "Thanh toan don dat: {$vnp_TxnRef}";
        $vnp_OrderType = "other";
        $vnp_Amount = $request->totalPrice * 100;
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

        if (isset($vnp_BankCode) && $vnp_BankCode != "") {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }
        if (isset($vnp_Bill_State) && $vnp_Bill_State != "") {
            $inputData['vnp_Bill_State'] = $vnp_Bill_State;
        }

        //var_dump($inputData);
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
        $isCreatedReservation = $this->reservationService->createNewReservation(($request));
        if (isset($_POST['redirect'])) {
            header('Location: ' . $vnp_Url);
            die();
        }
        if (!$isCreatedReservation) {
            return $this->respondWithErrorMessage('Create reservation Failed');
        }
        return $this->respond($returnData, "Payment Request");
    }
}
