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
use App\Models\Reservation;
use App\Repositories\Reservation\IReservationRepository;
use App\Repositories\Room\IRoomRepository;
use App\Repositories\RoomType\IRoomTypeRepository;
use App\Repositories\User\UserRepositoryInterface;
use PDO;
use tidy;

class ReservationService implements IReservationService
{
    use ResponseApi;

    public function __construct(
        private readonly IHotelRepo $hotelRepo,
        private readonly IReservationRepository $reservationRepo,
        private readonly UserRepositoryInterface $userRepo,
        private readonly IRoomTypeRepository $roomTypeRepo,
        private readonly IRoomRepository $roomRepo,
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
                'role' => RoleEnum::USER_NOT_REGISTER->value,
                'name' => $data['name'],
            ]);
        } else {
            $user = $userExisted;
        }

        //check room in roomtypes list is valid on the order day
        foreach ($data['roomTypeReservedList'] as $roomTypes) {
            //get all room in pending reservation and check expire time
            // $availableRooms = $this->roomTypeRepo
            //     ->getRoomAvailableForRoomTypes(new RoomAvailableOption(
            //         $roomTypes['id'],
            //         $roomTypes['count'],
            //         $checkInDay,
            //         $checkOutDay,
            //     ));
            $availableRooms = $this->roomRepo->getRoomAvaiable($roomTypes['id'], $checkInDay, $checkOutDay, $roomTypes['count']);
            // // dd($availableRooms);
            // if (!$availableRooms->count()) {
            //     throw new ResponseException("Room types not found");
            // }
            // dd($availableRooms);
            if ($availableRooms->count() < $roomTypes['count']) {
                throw new ResponseException("Not enough rooms");
            }
            // dd($availableRooms);
            return $this->reservationRepo->createNewReservation(
                [
                    'email' => $data['email'],
                    'tax_paid' => $data['tax'],
                    'status' => ReservationStatusEnum::PENDING->value,
                    'total_price' => $data['totalPrice'],
                    'user' => $user,
                    'rooms' => $availableRooms,
                    'start_day' => Carbon::parse($data['checkInDay']),
                    'end_day' => Carbon::parse($data['checkOutDay']),
                ]
            );
            // dd($availableRoom);
        }
    }

    public function getReservationByCode(string $code)
    {
        $reservation = $this->reservationRepo->findBy('reservation_code', $code, ['rooms']);
        return $reservation;
    }

    public function getReservationDetails(string $reservationId = '', Reservation $reservation = null)
    {
        if ($reservationId) {
            $reservationFounded = $this->reservationRepo->find($reservationId, relations: ['rooms']);
        } else if ($reservation) {
            $reservationFounded = $this->reservationRepo->find($reservation->id, relations: ['rooms']);
        }
        dd($reservationFounded['rooms']->count());

        //get hotel
        //get roomtypes list
        //get reservation
        //get user
    }
}
