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
use App\Repositories\RoomType\IRoomTypeRepository;
use App\Services\Images\ImageServiceInterface;

class HotelService implements IHotelService
{
    use ResponseApi;

    public function __construct(
        private readonly IHotelRepo $hotelRepo,
        private readonly ImageServiceInterface $imageService,
        private readonly IRoomTypeRepository $roomTypeRepo,
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
                    if ($hotel->id) {
                        $images = $this->imageService->getObjectImages($hotel->id);
                        $hotel->images = $images;
                        $hotel->average_rating = round($hotel->reviews->avg('rating'), 1);
                        $hotel->address = $hotel->street . ',' .  $hotel->ward . ',' .  $hotel->district . ',' . $hotel->province;
                        // $hotel->min_price = $hotel->roomTypes->min('price'); //get the min price of the roomTypes valid

                    }
                    if (!empty($query['min_price']) && !empty($query['max_price'])) {
                        foreach ($hotel->roomTypes as $roomType) {
                            // Kiểm tra xem giá có nằm trong khoảng min_price và max_price hay không

                            if (($roomType->price >= $query['min_price']) && $roomType->price <= $query['max_price']) {
                                // Nếu là lần lặp đầu tiên hoặc giá nhỏ nhất hiện tại lớn hơn giá của roomType này
                                if (!isset($minPriceRoomType) || $roomType->price < $minPriceRoomType->price) {
                                    $minPriceRoomType = $roomType; // Gán roomType này là roomType có giá nhỏ nhất
                                }
                            }
                        }
                        $hotel->min_price = $minPriceRoomType->price;
                    } else {
                        $hotel->min_price  = $hotel->roomTypes->min('price');
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
            $hotel = $this->hotelRepo->getHotelById($id);
            if ($hotel->id) {
                $images = $this->imageService->getObjectImages($hotel->id);
                $hotel->images = $images;
                $hotel->average_rating = round($hotel->reviews->avg('rating'), 1);
                $hotel->address = $hotel->street . ',' .  $hotel->ward . ',' .  $hotel->district . ',' . $hotel->province;
                $hotel->min_price = $hotel->roomTypes->min('price');
                if ($hotel->roomTypes) {
                    foreach ($hotel->roomTypes as $roomType) {
                        //get free room
                        $roomType->images = $this->imageService->getObjectImages($roomType->id);
                    }
                }
            }
            return $hotel;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
