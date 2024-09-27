<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

//repositories
use App\Repositories\User\UserRepository;
use App\Repositories\User\UserRepositoryInterface;

use App\Repositories\PasswordReset\PasswordResetRepository;
use App\Repositories\PasswordReset\PasswordResetRepositoryInterface;

use App\Repositories\Hotel\IHotelRepo;
use App\Repositories\Hotel\HotelRepo;

use App\Repositories\Payment\IPaymentRepository;
use App\Repositories\Payment\PaymentRepository;

use App\Repositories\Reservation\IReservationRepository;
use App\Repositories\Reservation\ReservationRepository;

use App\Repositories\RoomType\IRoomTypeRepository;
use App\Repositories\RoomType\RoomTypeRepository;

use App\Repositories\Room\IRoomRepository;
use App\Repositories\Room\RoomRepository;

use App\Repositories\Invoice\IInvoiceRepository;
use App\Repositories\Invoice\InvoiceRepository;

//services
use App\Services\Auth\AuthService;
use App\Services\Auth\AuthServiceInterface;

use App\Services\Hotel\IHotelService;
use App\Services\Hotel\HotelService;

use App\Services\Reservation\IReservationService;
use App\Services\Reservation\ReservationService;

use App\Services\Room\IRoomService;
use App\Services\Room\RoomService;

use App\Services\Payment\IPaymentService;
use App\Services\Payment\PaymentService;

use App\Services\User\IUserService;
use App\Services\User\UserService;

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
        $this->app->bind(IPaymentRepository::class, PaymentRepository::class);
        $this->app->bind(IReservationRepository::class, ReservationRepository::class);
        $this->app->bind(IRoomTypeRepository::class, RoomTypeRepository::class);
        $this->app->bind(IRoomRepository::class, RoomRepository::class);
        $this->app->bind(IInvoiceRepository::class, InvoiceRepository::class);

        // Services
        $this->app->bind(AuthServiceInterface::class, AuthService::class);
        $this->app->bind(IHotelService::class, HotelService::class);
        $this->app->bind(IReservationService::class, ReservationService::class);
        $this->app->bind(IRoomService::class, RoomService::class);
        $this->app->bind(IPaymentService::class, PaymentService::class);
        $this->app->bind(IUserService::class, UserService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void {}
}
