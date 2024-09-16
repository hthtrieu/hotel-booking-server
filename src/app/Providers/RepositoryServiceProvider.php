<?php

namespace App\Providers;

use App\Models\Reservation;
use Illuminate\Support\ServiceProvider;

//repositories
use App\Repositories\User\UserRepository;
use App\Repositories\User\UserRepositoryInterface;

use App\Repositories\PasswordReset\PasswordResetRepository;
use App\Repositories\PasswordReset\PasswordResetRepositoryInterface;

use App\Repositories\Hotel\IHotelRepo;
use App\Repositories\Hotel\HotelRepo;
//services
use App\Services\Auth\AuthService;
use App\Services\Auth\AuthServiceInterface;

use App\Services\Hotel\IHotelService;
use App\Services\Hotel\HotelService;

use App\Services\Reservation\IReservationService;
use App\Services\Reservation\ReservationService;

use App\Services\Room\IRoomService;
use App\Services\Room\RoomService;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Repositories
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(PasswordResetRepositoryInterface::class, PasswordResetRepository::class);
        $this->app->bind(IHotelRepo::class, HotelRepo::class);

        // Services
        $this->app->bind(AuthServiceInterface::class, AuthService::class);
        $this->app->bind(IHotelService::class, HotelService::class);
        $this->app->bind(IReservationService::class, ReservationService::class);
        $this->app->bind(IRoomService::class, RoomService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void {}
}
