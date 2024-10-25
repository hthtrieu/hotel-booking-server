<?php

use App\Enums\RoleEnum;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Hotel\HotelController;
use App\Http\Controllers\Reservation\ReservationController;
use App\Http\Controllers\Payment\PaymentController;
use App\Http\Controllers\Upload\UploadController;
use App\Http\Controllers\User\UserController;

require __DIR__ . '/auth.php';


Route::group([], function () {
    Route::resource('hotels', HotelController::class);
    Route::resource('reservations', ReservationController::class);
    // Prefix for payments routes
    Route::group(['prefix' => 'payments'], function () {
        Route::post('/success', [PaymentController::class, 'paymentSuccess']);
        Route::post('/refund', [PaymentController::class, 'refund']);
    });
    // Separate resource route for payments (not inside the group with prefix)
    Route::resource('payments', PaymentController::class);

    Route::group(['prefix' => 'uploads'], function () {
        Route::post('/get-presigned-url', [UploadController::class, 'getPresignedURL']);
        Route::post('/test-get-url', [UploadController::class, 'getImageURL']);
        Route::post('/test-upload', [UploadController::class, 'store']);
    });

    Route::resource('reservations', ReservationController::class);

    //! for coding front-end
    Route::group(['prefix' => 'user'], function () {
        Route::get('/reservations', [UserController::class, 'getUserReservationByStatus']);
        Route::get('/profile', [UserController::class, 'profile']);
        Route::put('/profile/update/{id}', [UserController::class, 'updateProfile']);
    });

    Route::get('/test-mail', [ReservationController::class, 'sendMail']);
});

Route::group(['middleware' => ['jwt.auth', 'jwt.auth:' . RoleEnum::USER->value]], function () {
    // Route::group(['prefix' => 'user'], function () {
    //    Route::get('/hitory', [UserController::class, 'getUserHistoryReservation']);
    // Route::get('/profile', [UserController::class, 'profile']);
    // });
});
