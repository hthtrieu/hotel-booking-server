<?php

namespace App\Services\Hotel;

use App\ApiCode;
use App\Http\Requests\Hotels\HotelSearchRequest;
use App\Traits\ResponseApi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Repositories\Hotel\IHotelRepo;
use App\Helpers\CheckUUIDFormat;

class HotelService implements IHotelService
{
    use ResponseApi;

    public function __construct(
        private readonly IHotelRepo $hotelRepo,
    ) {}

    public function getHotelsList(HotelSearchRequest $request)
    {
        try {
            $query = $request->all();
            if (empty($query['checkin']) || empty($query['checkout']) || empty($query['province'])) {
                return $this->respondBadRequest(apiCode::MISSING_REQUEST_PARAMS);
            } else {
                // if ($query['checkin'] < Carbon::now('+7')->format('Y-m-d')) {
                // }
                $roomMatched =  $this->hotelRepo->getHotelsList($query);
                return $roomMatched;
            }
            //get hotel
        } catch (\Throwable $th) {
            return $this->respondWithError(apiCode::SOMETHING_WENT_WRONG, 404);
        }
    }

    public function getHotelById(string $id)
    {
        try {
            return $this->hotelRepo->getHotelById($id);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
