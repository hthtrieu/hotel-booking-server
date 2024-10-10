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
use App\Services\Images\ImageServiceInterface;

class HotelService implements IHotelService
{
    use ResponseApi;

    public function __construct(
        private readonly IHotelRepo $hotelRepo,
        private readonly ImageServiceInterface $imageService,
    ) {}

    public function getHotelsList(HotelSearchRequest $request)
    {
        try {
            $query = $request->all();
            if (empty($query['checkin']) || empty($query['checkout']) || empty($query['province'])) {
                return $this->respondBadRequest(apiCode::MISSING_REQUEST_PARAMS);
            } else {
                $hotelMatched =  $this->hotelRepo->getHotelsList($query);
                foreach ($hotelMatched as $hotel) {
                    //get images
                    if ($hotel['hotel']->id) {
                        $images = $this->imageService->getHotelImages($hotel['hotel']->id);
                        $hotel['hotel']->images = $images;
                    }
                }
                return $hotelMatched;
            }
        } catch (\Throwable $th) {
            dd($th);
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
