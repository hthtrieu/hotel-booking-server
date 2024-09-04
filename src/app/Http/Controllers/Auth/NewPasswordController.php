<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ChangePasswordRequest;
use App\Services\Auth\AuthServiceInterface;

class NewPasswordController extends Controller
{
    public function __construct(
        private readonly AuthServiceInterface $authService
    ) {}

    /**
     * @OA\Post(
     *     path="/api/v1/auth/reset-password",
     *     tags={"auth"},
     *     summary="User request reset password",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password","confirmPassword","otp"},
     *             @OA\Property(property="email", type="string", example="admin@admin.vn"),
     *             @OA\Property(property="password", type="string", example="password"),
     *             @OA\Property(property="econfirmPasswordmail", type="string", example="econfirmPasswordmail"),
     *             @OA\Property(property="otp", type="number", example="123456"),
     * )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid email supplied"
     *     )
     * )
     */
    public function store(ChangePasswordRequest $request)
    {
        return $this->authService->resetPassword($request);
    }
}
