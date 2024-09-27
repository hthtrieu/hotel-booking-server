<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ResponseApi;
use App\Services\Payment\IPaymentService;
use App\ApiCode;
use App\Http\Requests\Reservation\CreateReservationRequest;

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
        return $this->paymentService->createPaymentRequest($request);
        // if ($urlDataArray) {
        //     return $this->respond($urlDataArray, "Payment Request");
        // } else {
        //     return $this->respondWithError(ApiCode::DATA_NOT_FOUND, 404);
        // }
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
        return $this->paymentService->paymentSuccess($request);
    }
}
