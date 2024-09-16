<?php

use App\Enums\RoleEnum;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Hotel\HotelController;
use App\Http\Controllers\Reservation\ReservationController;
use App\Http\Controllers\Payment\PaymentController;

require __DIR__ . '/auth.php';


Route::group([], function () {
    // Route::group(['prefix' => 'hotels'], function () {
    //     Route::get('', [HotelController::class, 'index']);
    // });
    Route::resource('hotels', HotelController::class);
    Route::resource('reservations', ReservationController::class);
    Route::resource('payments', PaymentController::class);
});

// Route::group(['middleware' => ['jwt.auth', 'jwt.auth:' . RoleEnum::ADMINISTRATOR->value]], function () {
//     Route::group(['prefix' => 'user'], function () {
//         Route::group(['prefix' => 'admin'], function () {
//             // Route::get('', [UserController::class, 'index']);
//         });
//     });
// });

// Route::group(['middleware' => ['jwt.auth']], function () {
//     Route::group(['prefix' => 'user'], function () {
//         // Route::get('/{id}', [UserController::class, 'show_by_user']);
//         // Route::post('/change-password', [UserController::class, 'change_password_by_user']);
//         // Route::put('/{id}', [UserController::class, 'update_by_user']);
//     });
// });
