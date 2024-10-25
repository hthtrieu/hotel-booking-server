<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ResponseApi;
use App\Services\Payment\IPaymentService;
use App\ApiCode;
use App\Dtos\Payment\CreateRefundRequestDTO;
use App\Dtos\Reservation\CreateReservationRequestDTO;
use App\Http\Requests\Payment\RefundRequest;
use App\Http\Requests\Reservation\CreateReservationRequest;
use App\Http\Resources\ReservationDetailsResponse;

class PaymentController extends Controller
{
    use ResponseApi;
    public function __construct(
        private readonly IPaymentService $paymentService,
    ) {}
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }


    public function create()
    {
        //
    }

    /**
     * create new url for payment/order with vnpay
     */
    /**
     * @OA\Post(
     *     path="/api/v1/payments",
     *     tags={"payment"},
     *     summary="Create payment request url for user's order",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"price"},
     *             @OA\Property(property="price", type="numeric", example="500.000"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid request params supplied"
     *     )
     * )
     */
    public function store(CreateReservationRequest $request)
    {
        $request->validated();

        $createReservationDTO = new CreateReservationRequestDTO(
            $request->input('hotel_id'),
            $request->input('note') || "",
            $request->input('name'),
            $request->input('email'),
            $request->input('phoneNumber'),
            $request->input('paymentMethod') || "CREDIT CARD",
            $request->input('roomTypeReservedList'),
            $request->input('totalPrice'),
            $request->input('tax'),
            $request->input('vat'),
            $request->input('checkInDay'),
            $request->input('checkOutDay')
        );
        $data = $this->paymentService->createPaymentRequest($createReservationDTO, $request);
        return $this->respond($data, "Reservation is pending");
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    /**
     * @OA\Post(
     *     path="/api/v1/payments/success",
     *     tags={"payment"},
     *     summary="Send info for updating and create invoice if payment successfuly",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"price"},
     *             @OA\Property(property="price", type="numeric", example="500.000"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid request params supplied"
     *     )
     * )
     */
    public function paymentSuccess(Request $request)
    {
        $invoice = $this->paymentService->paymentSuccess($request);

        if ($invoice) {
            return $this->respond(
                new ReservationDetailsResponse($invoice),
                "invoice info"
            );
        }
        return $this->respondWithErrorMessage("Error");
    }
    public function refund(RefundRequest $request)
    {
        // $request->validate();
        $refundRequest = new CreateRefundRequestDTO(
            $request->input('order_id'),
            $request->input('price'),
            $request->input('transaction_type'),
            $request->input('user')
            // $request->input('transaction_date'),
        );
        // dd($refundRequest);
        $invoice = $this->paymentService->refund($refundRequest);
        if ($invoice) {
            return $this->respond(
                new ReservationDetailsResponse($invoice),
                "Invoice Refund"
            );
        }
        return $this->respondWithErrorMessage("Error");
    }
}
