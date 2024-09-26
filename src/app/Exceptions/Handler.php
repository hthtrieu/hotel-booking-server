<?php

namespace App\Exceptions;

use App\ApiCode;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;
use Symfony\Component\HttpFoundation\Response;
use App\Exceptions\ResponseException;
use Throwable;

class Handler extends ExceptionHandler
{
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register(): void
    {
        // Xử lý ngoại lệ JWT nếu có
        $this->renderable(function (Throwable $e, $request) {
            return (new JWTExceptionHandler($this->container))->render($request, $e);
        });
    }

    public function render($request, Throwable $exception)
    {
        // Xử lý lỗi validation
        if ($exception instanceof ValidationException) {
            return $this->respondWithValidationError($exception);
        }

        // Xử lý lỗi DataNotFoundException
        if ($exception instanceof DataNotFoundException) {
            return $this->respondWithNotFound($exception);
        }
        // Xử lý lỗi logic
        if ($exception instanceof ResponseException) {
            return $this->respondException($exception);
        }

        return parent::render($request, $exception);
    }

    private function respondWithValidationError(ValidationException $exception)
    {
        return ResponseBuilder::asError(ApiCode::VALIDATION_ERROR)
            ->withData($exception->errors())
            ->withHttpCode(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->build();
    }

    private function respondWithNotFound(DataNotFoundException $exception)
    {
        return ResponseBuilder::asError(ApiCode::DATA_NOT_FOUND)
            ->withMessage($exception->getMessage())
            ->withHttpCode(Response::HTTP_NOT_FOUND)
            ->build();
    }
    private function respondException(ResponseException $exception)
    {
        return ResponseBuilder::asError(ApiCode::SOMETHING_WENT_WRONG)
            ->withMessage($exception->getMessage())
            ->withHttpCode(Response::HTTP_NOT_FOUND)
            ->build();
    }
}
