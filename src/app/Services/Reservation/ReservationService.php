<?php

namespace App\Services\Reservation;

use App\Dtos\Invoice\InvoiceDTO;
use App\Enums\ReservationStatusEnum;
use App\Enums\RoleEnum;
use App\Exceptions\ResponseException;
use App\Traits\ResponseApi;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Repositories\Hotel\IHotelRepo;
use App\Helpers\DayTimeHelper;
use App\Repositories\Reservation\IReservationRepository;
use App\Repositories\Room\IRoomRepository;
use App\Repositories\RoomType\IRoomTypeRepository;
use App\Repositories\User\UserRepositoryInterface;
use App\Services\Hotel\IHotelService;
use App\Dtos\Reservation\CreateReservationRequestDTO;
use App\Dtos\Reservation\ReservationDTO;
use App\Dtos\Reservation\ReservationResponseDTO;
use App\Dtos\Room\RoomTypeDTO;
use App\Dtos\User\UserDTO;
use App\Exceptions\DataNotFoundException;
use App\Http\Resources\ReservationDetailsResponse;
use App\Mail\ReservationConfirmMail;
use App\Mail\UserActivationEmail;
use App\Repositories\Invoice\IInvoiceRepository;


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
        private readonly IInvoiceRepository $invoiceRepo,
    ) {}

    public function createNewReservation(CreateReservationRequestDTO $data)
    {
        $checkInDay = Carbon::parse($data->checkInDay);
        $checkOutDay = Carbon::parse($data->checkOutDay);
        //check user is existed
        $userExisted = $this->userRepo->findBy('email', $data->email);
        if (!$userExisted) {
            //if not -> save new user
            $user = $this->userRepo->store([
                'email' => $data->email,
                'phone_number' => $data->phoneNumber,
                'password' => '',
                'address' => '',
                'role' => RoleEnum::USER_NOT_REGISTER->value,
                'name' => $data->name,
            ]);
        } else {
            $user = $userExisted;
        }

        //check room in roomtypes list is valid on the order day
        foreach ($data->roomTypeReservedList as $roomTypes) {

            $availableRooms = $this->roomRepo->getRoomAvaiable($roomTypes->id, $checkInDay, $checkOutDay, $roomTypes->count);

            if ($availableRooms->count() < $roomTypes->count) {
                throw new ResponseException("Not enough rooms");
            }
            // dd($availableRooms);
            return $this->reservationRepo->createNewReservation(
                [
                    'email' => $data->email,
                    'tax_paid' => $data->tax,
                    'status' => ReservationStatusEnum::PENDING->value,
                    'total_price' => $data->totalPrice,
                    'user' => $user,
                    'rooms' => $availableRooms,
                    'start_day' => Carbon::parse($data->checkInDay),
                    'end_day' => Carbon::parse($data->checkOutDay),
                ]
            );
            // dd($availableRoom);
        }
    }

    public function getInvoiceByReservationId(string $id): ReservationResponseDTO
    {
        $reservation = $this->reservationRepo->findBy(
            'id',
            $id,
            ['rooms', 'user', 'invoice', 'rooms.roomTypes', 'rooms.reservations']
        );
        if (!$reservation) {
            throw new DataNotFoundException("Reservation not Found");
        }
        $hotelDTO = $this->hotelService->getHotelById($reservation->rooms[0]->roomTypes->hotel->id);
        $roomTypesDTO = collect(); // Khởi tạo Collection

        foreach ($reservation->rooms as $room) {
            $roomTypeId = $room->roomTypes->id;
            $startDate = Carbon::parse($room->pivot->start_day);
            $endDate = Carbon::parse($room->pivot->end_day);
            $daysCount = $startDate->diffInDays($endDate) + 1;

            $roomType = $room->roomTypes;
            $roomType->days_count = $daysCount;
            $roomType->total_rooms = isset($roomTypesDTO[$roomTypeId]) ? $roomTypesDTO[$roomTypeId]->total_rooms + 1 : 1;
            $roomTypesDTO->put($roomTypeId, RoomTypeDTO::fromModel($roomType));
        }
        $roomTypesDTOArray = $roomTypesDTO->values()->toArray();

        $reservationStartEndDay = $this->getReservationStartEndDayById($reservation->id);
        $startDay = DayTimeHelper::formatStringDateTime($reservationStartEndDay['start_day'], 'Y-m-d H:i:s');
        $endDay = DayTimeHelper::formatStringDateTime($reservationStartEndDay['end_day'], 'Y-m-d H:i:s');
        $nightCount = Carbon::parse($endDay)->diffInDays(Carbon::parse($startDay)) + 1;
        $reservationData = (object) [
            'id' => $reservation->id,
            'reservation_code' => $reservation->reservation_code,
            'site_fees' => $reservation->site_fees,
            'tax_paid' => $reservation->tax_paid,
            'total_price' => $reservation->total_price,
            'status' => $reservation->status->value,
            'checkin' => DayTimeHelper::formatStringDateTime($reservation->rooms[0]->pivot->start_day, 'Y-m-d H:i:s', 'Y-m-d'),
            'checkout' => DayTimeHelper::formatStringDateTime($reservation->rooms[0]->pivot->end_day, 'Y-m-d H:i:s', 'Y-m-d'),
            'night_count' => $nightCount,
        ];
        $invoiceData = (object) [
            'hotelDTO' => $hotelDTO,
            'userDTO' => UserDTO::fromModel($reservation->user),
            'roomTypesDTO' => $roomTypesDTOArray,
            'invoiceDTO' => InvoiceDTO::fromModel($reservation->invoice),
            'reservationDTO' => ReservationDTO::fromData($reservationData),
        ];
        // dd($invoiceData->roomTypesDTO);
        $reservationResponseDTO = ReservationResponseDTO::fromModel(($invoiceData));
        return $reservationResponseDTO;
    }

    public function getReservationByCode(string $code)
    {
        $reservation = $this->reservationRepo->findBy('reservation_code', $code);
        if ($reservation) {
            return $this->getInvoiceByReservationId($reservation->id);
        }
        return null;
    }

    public function checkValidReservationAmount(CreateReservationRequestDTO $data)
    {
        //check total price of roomTypes
        $roomTypes = $data->roomTypeReservedList;
        $totalPrice = 0;
        foreach ($roomTypes as $roomType) {
            $validRoomType = $this->roomTypeRepo->find($roomType->id);
            if ($validRoomType->price !== $roomType->price) {
                throw new ResponseException("RommType Price not correct");
            }
            $totalPrice += $roomType->price * $roomType->count;
        }
        if ($totalPrice !== $data->totalPrice) {
            throw new ResponseException("Total price not correct");
        }
        $tax = $totalPrice * 0.1;
        if ($tax !== $data->tax) {
            throw new ResponseException("Tax price not correct");
        }
        $vatPrice = $totalPrice + $tax;
        if ($vatPrice === $data->vat) {
            return true;
        } else return false;
    }

    public function getReservationStartEndDayById(string $reservationId)
    {
        $reservation = $this->reservationRepo->find($reservationId, relations: ['rooms']);
        $data = [
            'start_day' => $reservation->rooms[0]->pivot->start_day,
            'end_day' => $reservation->rooms[0]->pivot->end_day,
        ];
        return $data;
    }

    public function TestSendMail()
    {
        $user = $this->userRepo->findBy(
            'role',
            RoleEnum::USER->value
        );
        $reservation = $this->reservationRepo->findByMany(
            [
                'status' => ReservationStatusEnum::CONFIRMED,
                'user_id' => $user->id
            ],
        );
        $data = $this->getInvoiceByReservationId($reservation->id);
        // dd($reservation);
        if ($data) {
            Mail::to($user->email)->send(new ReservationConfirmMail($data));
            return new ReservationDetailsResponse($data);
        } else {
            return null;
        }
    }
}
