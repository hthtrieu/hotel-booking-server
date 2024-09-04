<?php

namespace App\Exceptions;

use App\ApiCode;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->renderable(function (Throwable $e, $request) {
            return (new JWTExceptionHandler($this->container))->render($request, $e);
        });
    }

    /**
     * Report or log an exception.
     *
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof ValidationException) {
            return $this->respondWithValidationError($exception);
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
}
