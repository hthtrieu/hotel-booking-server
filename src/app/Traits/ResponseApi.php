<?php

namespace App\Traits;

use Illuminate\Http\Response;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;

trait ResponseApi
{
    public function respond($data, $msg = null)
    {
        return ResponseBuilder::asSuccess()->withData($data)->withMessage($msg)->build();
    }

    public function respondNoContent()
    {
        return response()->noContent();
    }

    public function respondWithMessage($msg)
    {
        return ResponseBuilder::asSuccess()->withMessage($msg)->build();
    }

    public function respondWithError($apiCode, $httpCode)
    {
        return ResponseBuilder::asError($apiCode)->withHttpCode($httpCode)->build();
    }

    public function respondBadRequest($apiCode)
    {
        return $this->respondWithError($apiCode, Response::HTTP_BAD_REQUEST);
    }

    public function respondUnAuthorizedRequest($apiCode)
    {
        return $this->respondWithError($apiCode, Response::HTTP_UNAUTHORIZED);
    }

    public function respondNotFound($apiCode)
    {
        return $this->respondWithError($apiCode, Response::HTTP_NOT_FOUND);
    }
}
