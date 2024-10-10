<?php

use App\Enums\RoleEnum;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Hotel\HotelController;
use App\Http\Controllers\Reservation\ReservationController;
use App\Http\Controllers\Payment\PaymentController;
use App\Http\Controllers\Upload\UploadController;

require __DIR__ . '/auth.php';


Route::group([], function () {
    Route::resource('hotels', HotelController::class);
    Route::resource('reservations', ReservationController::class);
    // Prefix for payments routes
    Route::group(['prefix' => 'payments'], function () {
        Route::post('/success', [PaymentController::class, 'paymentSuccess']);
    });
    // Separate resource route for payments (not inside the group with prefix)
    Route::resource('payments', PaymentController::class);

    Route::group(['prefix' => 'uploads'], function () {
        Route::post('/get-presigned-url', [UploadController::class, 'getPresignedURL']);
        Route::post('/test-get-url', [UploadController::class, 'getImageURL']);
        Route::post('/test-upload', [UploadController::class, 'store']);
    });
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
