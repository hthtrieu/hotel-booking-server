<?php

namespace App\Services\Reservation;

use App\ApiCode;
use App\Dtos\Room\RoomAvailableOption;
use App\Enums\ReservationStatusEnum;
use App\Enums\RoleEnum;
use App\Exceptions\ResponseException;
use App\Traits\ResponseApi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Repositories\Hotel\IHotelRepo;
use App\Helpers\CheckUUIDFormat;
use App\Http\Requests\Reservation\CreateReservationRequest;
use App\Repositories\Reservation\IReservationRepository;
use App\Repositories\RoomType\IRoomTypeRepository;
use App\Repositories\User\UserRepositoryInterface;

class ReservationService implements IReservationService
{
    use ResponseApi;

    public function __construct(
        private readonly IHotelRepo $hotelRepo,
        private readonly IReservationRepository $reservationRepo,
        private readonly UserRepositoryInterface $userRepo,
        private readonly IRoomTypeRepository $roomTypeRepo,
    ) {}

    public function createNewReservation(CreateReservationRequest $request)
    {
        $data = $request->validated();
        $checkInDay = Carbon::parse($data['checkInDay']);
        $checkOutDay = Carbon::parse($data['checkOutDay']);
        //check user is existed
        $userExisted = $this->userRepo->findBy('email', $data['email']);
        if (!$userExisted) {
            //if not -> save new user
            $user = $this->userRepo->store([
                'email' => $data['email'],
                'phone_number' => $data['phoneNumber'],
                'password' => '',
                'address' => '',
                'role' => RoleEnum::USER->value,
                'name' => $data['name'],
            ]);
        } else {
            $user = $userExisted;
        }

        //check room in roomtypes list is valid on the order day
        foreach ($data['roomTypeReservedList'] as $roomTypes) {
            $availableRooms = $this->roomTypeRepo
                ->getRoomAvailableForRoomTypes(new RoomAvailableOption(
                    $roomTypes['id'],
                    $roomTypes['count'],
                    $checkInDay,
                    $checkOutDay,
                ));
            if (!$availableRooms->count() || $availableRooms[0]['rooms']->count() < $roomTypes['count']) {
                throw new ResponseException("Not enough rooms");
            }
            return $this->reservationRepo->createNewReservation(
                [
                    'email' => $data['email'],
                    'tax_paid' => $data['tax'],
                    'status' => ReservationStatusEnum::PENDING->value,
                    'total_price' => $data['totalPrice'],
                    'user' => $user,
                    'rooms' => $availableRooms[0]['rooms'],
                    'start_day' => Carbon::parse($data['checkInDay']),
                    'end_day' => Carbon::parse($data['checkOutDay']),
                ]
            );

            // dd($availableRoom);
        }
    }
}
