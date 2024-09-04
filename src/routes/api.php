<?php

use App\Enums\RoleEnum;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

require __DIR__ . '/auth.php';


Route::group([], function () {
});

Route::group(['middleware' => ['jwt.auth', 'jwt.auth:' . RoleEnum::ADMINISTRATOR->value]], function () {
    Route::group(['prefix' => 'user'], function () {
        Route::group(['prefix' => 'admin'], function () {
            // Route::get('', [UserController::class, 'index']);
        });
    });
});

Route::group(['middleware' => ['jwt.auth']], function () {
    Route::group(['prefix' => 'user'], function () {
        // Route::get('/{id}', [UserController::class, 'show_by_user']);
        // Route::post('/change-password', [UserController::class, 'change_password_by_user']);
        // Route::put('/{id}', [UserController::class, 'update_by_user']);
    });
});
