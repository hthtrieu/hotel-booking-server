<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\Auth\AuthServiceInterface;

class AuthController extends Controller
{
    public function __construct(
        private readonly AuthServiceInterface $authService,
    ) {
        $this->middleware(['jwt.auth'], ['except' => ['login', 'register']]);
    }
    /**
     * @OA\Post(
     *     path="/api/v1/auth/login",
     *     tags={"auth"},
     *     summary="Logs user into system",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", example="admin@admin.vn"),
     *             @OA\Property(property="password", type="string", example="password")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid username/password supplied"
     *     )
     * )
     */

    public function login(LoginRequest $request)
    {
        return $this->authService->login($request);
    }
    /**
     * @OA\Get(
     *     path="/api/v1/auth/logout",
     *     tags={"auth"},
     *     summary="User logout",
     *     security={{"bearerAuth": {}, "API_Key_Authorization": {}}},
     *  @OA\Response(
     *         response=200,
     *         description="User successfully logged out",
     *     ),
     * )
     */
    public function logout()
    {
        return $this->authService->logout();
    }

    public function refresh()
    {
        return $this->authService->refreshToken();
    }
    /**
     * @OA\Get(
     *     path="/api/v1/auth/me",
     *     tags={"auth"},
     *     summary="Get user profiles",
     *     security={{"bearerAuth": {}, "API_Key_Authorization": {}}},
     *  @OA\Response(
     *         response=200,
     *         description="User profile retrieved successfully",
     *     ),
     * )
     */
    public function me()
    {
        return $this->authService->getCurrentUser();
    }
    /**
     * @OA\Post(
     *     path="/api/v1/auth/register",
     *     tags={"auth"},
     *     summary="Register new user",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", example="user@email.com"),
     *             @OA\Property(property="password", type="string", example="password"),
     *            @OA\Property(property="address", type="string", example="Viet Nam"),
     *            @OA\Property(property="phone_number", type="string", example="0123456789"),
     *            @OA\Property(property="name", type="string", example="userNo1")

     * )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid username/password supplied"
     *     )
     * )
     */
    public function register(RegisterRequest $request)
    {
        return $this->authService->register($request);
    }
}
