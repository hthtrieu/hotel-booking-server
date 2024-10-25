<?php

namespace App\Services\User;

use App\ApiCode;
use App\Dtos\User\UpdateProfileRequestDTO;
use App\Enums\ReservationStatusEnum;
use App\Enums\RoleEnum;
use App\Traits\ResponseApi;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Helpers\CheckUUIDFormat;
use App\Http\Requests\User\UpdateProfileRequest;
use App\Models\Reservation;
use App\Repositories\Reservation\IReservationRepository;
use App\Repositories\User\UserRepositoryInterface;
use App\Services\Reservation\IReservationService;

class UserService implements IUserService
{
    use ResponseApi;

    public function __construct(
        private readonly UserRepositoryInterface $userRepo,
        private readonly IReservationRepository $reservationRepo,
        private readonly IReservationService $reservationService,
    ) {}

    public function getFirstUserForTesting()
    {
        return  $this->userRepo->findBy("role", RoleEnum::USER->value);
    }

    public function getUserProfile()
    {
        // Get the authenticated user from the JWT token
        // $userJWT = JWTAuth::parseToken()->authenticate();
        $userJWT = $this->getFirstUserForTesting();
        $user = $this->userRepo->find($userJWT->id, relations: ['reservations', 'invoices']);
        // Return user data (or any data you need)
        return $user;
    }

    public function updateProfile(UpdateProfileRequestDTO $data)
    {
        $userUpdated = $this->userRepo->update($data->id, [
            'email' => $data->email,
            'phone_number' => $data->phone_number,
            'name' => $data->name,
        ]);
        return $userUpdated;
    }

    public function getUserHistoryReservation(string $status = "")
    {
        // $userJWT = JWTAuth::parseToken()->authenticate();
        $userJWT = $this->getFirstUserForTesting();

        $reservations = $this->reservationRepo->getBy([
            'user_id' => $userJWT->id,
            'status' => $status
        ]);

        $result = [];
        if ($reservations->count()) {
            foreach ($reservations as $reservation) {
                $result[] = $this->reservationService->getReservationByCode($reservation->reservation_code);
            }
        }

        return $result; // Return an array of results
    }
}
