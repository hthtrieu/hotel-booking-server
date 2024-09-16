<?php

namespace App\Services\Reservation;

use App\ApiCode;
use App\Traits\ResponseApi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Repositories\Hotel\IHotelRepo;
use App\Helpers\CheckUUIDFormat;

class ReservationService implements IReservationService
{
    use ResponseApi;

    public function __construct(
        private readonly IHotelRepo $hotelRepo,
    ) {}
}
