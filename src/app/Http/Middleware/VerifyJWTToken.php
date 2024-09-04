<?php

namespace App\Http\Middleware;

use App\ApiCode;
use Closure;
use Illuminate\Http\Request;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class VerifyJWTToken
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $token = JWTAuth::parseToken();
        $user = $token->authenticate();
        if ($user && (! isset($roles) || (isset($roles) && in_array($user->role, $roles)))) {
            return $next($request);
        }

        return $this->respondWithJWTError(ApiCode::UNAUTHORIZED);
    }

    private function respondWithJWTError(int $errorCode)
    {
        return ResponseBuilder::asError($errorCode)
            ->withHttpCode(Response::HTTP_UNAUTHORIZED)
            ->build();
    }
}
