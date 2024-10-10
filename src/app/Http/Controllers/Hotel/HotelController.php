<?php

namespace App\Http\Controllers\Hotel;

use App\Http\Controllers\Controller;
use App\Services\Hotel;
use Illuminate\Http\Request;
use App\Http\Requests\Hotels\HotelSearchRequest;
use App\Services\Hotel\IHotelService;
use Error;
use App\ApiCode;
use App\Repositories\User\UserRepositoryInterface;
use App\Traits\ResponseApi;
use Carbon\Carbon;
use GuzzleHttp\Psr7\Request as Psr7Request;
use Illuminate\Http\Client\Request as ClientRequest;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Request as FacadesRequest;
use Tymon\JWTAuth\Facades\JWTAuth;
use Throwable;

class HotelController extends Controller
{
    use ResponseApi;
    public function __construct(
        private readonly IHotelService $hotelService,
    ) {}
    /**
     * @OA\Get(
     *     path="/api/v1/hotels",
     *     summary="Lấy danh sách khách sạn",
     *     tags={"hotels"},
     *     @OA\Parameter(
     *         name="province",
     *         in="query",
     *         required=true,
     *         description="Tên tỉnh hoặc thành phố, ví dụ: Thành phố Đà Nẵng",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="checkin",
     *         in="query",
     *         required=true,
     *         description="Ngày check-in, định dạng: Y-m-d",
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Parameter(
     *         name="checkout",
     *         in="query",
     *         required=true,
     *         description="Ngày check-out, định dạng: Y-m-d",
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Parameter(
     *         name="adult",
     *         in="query",
     *         required=true,
     *         description="Số lượng người lớn",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="children",
     *         in="query",
     *         required=false,
     *         description="Số lượng trẻ em",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="rooms",
     *         in="query",
     *         required=true,
     *         description="Số lượng phòng",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="rate",
     *         in="query",
     *         required=false,
     *         description="Điểm đánh giá của khách sạn",
     *         @OA\Schema(type="number", format="float")
     *     ),
     *     @OA\Parameter(
     *         name="min_price",
     *         in="query",
     *         required=false,
     *         description="Giá tối thiểu",
     *         @OA\Schema(type="number", format="float")
     *     ),
     *     @OA\Parameter(
     *         name="max_price",
     *         in="query",
     *         required=false,
     *         description="Giá tối đa",
     *         @OA\Schema(type="number", format="float")
     *     ),
     *     @OA\Parameter(
     *         name="review",
     *         in="query",
     *         required=false,
     *         description="Điểm đánh giá từ người dùng",
     *         @OA\Schema(type="number", format="float")
     *     ),
     *     @OA\Parameter(
     *         name="page_index",
     *         in="query",
     *         required=false,
     *         description="Trang hiện tại",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="page_size",
     *         in="query",
     *         required=false,
     *         description="Số lượng kết quả mỗi trang",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Danh sách khách sạn trả về",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="province", type="string"),
     *                 @OA\Property(property="price", type="number"),
     *                 @OA\Property(property="rating", type="number"),
     *                 @OA\Property(property="review_count", type="integer")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request"
     *     )
     * )
     */
    public function index(HotelSearchRequest $request)
    {
        try {
            $results =  $this->hotelService->getHotelsList($request);
            return $this->respond($results);
        } catch (Throwable $th) {
            return $this->respondWithError(ApiCode::SOMETHING_WENT_WRONG, 404);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * @OA\Get(
     *     path="/api/v1/hotels/{id}",
     *     tags={"hotels"},
     *     summary="Display the specified hotel",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of hotel that needs to be fetched",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             format="uuid",
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid supplied"
     *     )
     * )
     */
    public function show(string $id)
    {
        $hotel =  $this->hotelService->getHotelById($id);
        if ($hotel) {
            return $this->respond($hotel, 'get hotel success');
        } else {
            return $this->respondWithError(apiCode::DATA_NOT_FOUND, 404);
        }
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
}
