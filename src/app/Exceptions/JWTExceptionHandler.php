<?php

namespace App\Exceptions;

use App\ApiCode;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Response;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Throwable;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class JWTExceptionHandler extends ExceptionHandler
{
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof UnauthorizedHttpException) {
            $preException = $exception->getPrevious();
            if ($preException instanceof TokenBlacklistedException) {
                return $this->respondWithJWTError('Token can not be used, get new one.');
            } elseif ($preException instanceof TokenExpiredException) {
                return $this->respondWithJWTError('Token is expired.');
            } elseif ($preException instanceof TokenInvalidException) {
                return $this->respondWithJWTError('Token is invalid.');
            } else {
                return $this->respondWithJWTError('Token is not provided.');
            }
        }

        return parent::render($request, $exception);
    }

    private function respondWithJWTError(string $message)
    {
        return ResponseBuilder::asError(ApiCode::UNAUTHORIZED)
            ->withMessage($message)
            ->withHttpCode(Response::HTTP_UNAUTHORIZED)
            ->build();
    }
}
