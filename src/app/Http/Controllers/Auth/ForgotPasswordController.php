<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Services\Auth\AuthServiceInterface;

class ForgotPasswordController extends Controller
{
    public function __construct(
        private readonly AuthServiceInterface $authService
    ) {}

    /**
     * Handle an incoming password reset link request.
     */
    /**
     * @OA\Post(
     *     path="/api/v1/auth/forgot-password",
     *     tags={"auth"},
     *     summary="User request forgot password",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", example="admin@admin.vn"),
     *         )
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
    public function store(ForgotPasswordRequest $request)
    {
        return $this->authService->forgotPassword($request);
    }
}
