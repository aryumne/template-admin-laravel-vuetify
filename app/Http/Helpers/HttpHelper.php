<?php

namespace App\Http\Helpers;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class HttpHelper
{
    public static function successResponse($message = 'Prosess Passed', $data = [], $code = Response::HTTP_OK)
    {
        $response = [
            'status'  => true,
            'message' => $message,
            'data'    => $data,
        ];
        return new JsonResponse($response, $code);
    }

    public static function errorValidation($message = 'Validation error', $errors = [], $code = Response::HTTP_UNPROCESSABLE_ENTITY)
    {
        $response = [
            'status'  => false,
            'message' => $message,
            'data'    => $errors,
        ];
        throw new HttpResponseException(response()->json($response, $code));
    }

    public static function errorResponse($message = 'Error internal server.', $errors = [], $code = Response::HTTP_BAD_REQUEST)
    {
        $response = [
            'status'  => false,
            'message' => $message,
            'data'    => $errors,
        ];
        return new JsonResponse($response, $code);
    }
}
