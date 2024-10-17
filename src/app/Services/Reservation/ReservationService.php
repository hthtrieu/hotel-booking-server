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
use App\Helpers\DayTimeHelper;
use App\Http\Requests\Reservation\CreateReservationRequest;
use App\Models\Reservation;
use App\Repositories\Reservation\IReservationRepository;
use App\Repositories\Room\IRoomRepository;
use App\Repositories\RoomType\IRoomTypeRepository;
use App\Repositories\User\UserRepositoryInterface;
use App\Services\Hotel\IHotelService;

class ReservationService implements IReservationService
{
    use ResponseApi;

    public function __construct(
        private readonly IHotelRepo $hotelRepo,
        private readonly IReservationRepository $reservationRepo,
        private readonly UserRepositoryInterface $userRepo,
        private readonly IRoomTypeRepository $roomTypeRepo,
        private readonly IRoomRepository $roomRepo,
        private readonly IHotelService $hotelService,
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

    public function getInvoiceByReservationId(string $id)
    {
        $reservation = $this->reservationRepo->findBy(
            'id',
            $id,
            ['rooms', 'user', 'invoice', 'rooms.roomTypes', 'rooms.reservations']
        );
        $hotel = $this->hotelService->getHotelById($reservation->rooms[0]->roomTypes->hotel->id);;
        unset($hotel['room_types']); // Loại bỏ thuộc tính 'hotel'
        $roomTypes = [];

        foreach ($reservation->rooms as $room) {
            $roomTypeId = $room->roomTypes->id;
            $startDate = Carbon::parse($room->pivot->start_day);
            $endDate = Carbon::parse($room->pivot->end_day);
            $daysCount = $startDate->diffInDays($endDate) + 1;

            // Chuyển đổi roomTypes thành mảng
            $roomTypeArray = $room->roomTypes->toArray();
            unset($roomTypeArray['hotel']); // Loại bỏ thuộc tính 'hotel'
            $roomTypeArray['days_count'] = $daysCount;
            $roomTypeArray['total_rooms'] = isset($roomTypes[$roomTypeId]) ? $roomTypes[$roomTypeId]['total_rooms'] + 1 : 1;
            $roomTypeArray['hotel'] = null;
            $roomTypes[$roomTypeId] = $roomTypeArray;
        }

        $reservationData = [
            'id' => $reservation->id,
            'reservation_code' => $reservation->reservation_code,
            'site_fees' => $reservation->site_fees,
            'tax_paid' => $reservation->tax_paid,
            'total_price' => $reservation->total_price,
            'status' => $reservation->status->value,
            'checkin' => DayTimeHelper::convertDateToString(DayTimeHelper::convertStringToDateTime($reservation->rooms[0]->pivot->start_day)),
            'checkout' => DayTimeHelper::convertDateToString(DayTimeHelper::convertStringToDateTime($reservation->rooms[0]->pivot->end_day)),
        ];
        $invoiceData = [
            'hotel' => $hotel,
            'user' => $reservation->user,
            'room_types' => array_values($roomTypes),
            'invoice' => $reservation->invoice,
            'reservation' => $reservationData,
        ];

        return $invoiceData;
    }
}
