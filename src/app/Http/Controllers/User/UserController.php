<?php

namespace App\Http\Controllers\User;

use App\Dtos\User\UpdateProfileRequestDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdateProfileRequest;
use App\Http\Resources\ReservationDetailsResponse;
use App\Http\Resources\User\UserReservationsResource;
use App\Services\User\IUserService;
use App\Traits\ResponseApi;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use ResponseApi;
    public function __construct(
        private readonly IUserService $userService,
    ) {}
    public function profile()
    {
        //get data from jwt -> decode and get user id -> find user by id
        $user = $this->userService->getUserProfile();
        if ($user) {
            return $this->respond([
                'user' => $user
            ], "user info");
        } else {
            return $this->respondWithErrorMessage("User not found");
        }
    }

    public function updateProfile(UpdateProfileRequest $request, string $id)
    {
        $data = $request->validated();
        $dto = new UpdateProfileRequestDTO($id, $data['email'], $data['name'], $data['phone_number']);
        // dd($dto);
        $result = $this->userService->updateProfile($dto);
        if ($result) {
            return $this->respond(["user" => $result], 'Updated User Profile');
        }
        return $this->respondWithErrorMessageMessage("Error");
    }

    public function getUserReservationByStatus(Request $request)
    {
        $status = $request->query("status");
        if (!$status) {
            return $this->respondWithErrorMessage("Status required");
        }
        $result = $this->userService->getUserHistoryReservation($status);
        if ($result) {
            return $this->respond([
                'data' => new UserReservationsResource($result)
            ], "user reservations");
        } else {
            return $this->respondWithErrorMessage("Reservation not found");
        }
    }
}
