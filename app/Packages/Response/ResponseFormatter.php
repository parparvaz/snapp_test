<?php

namespace App\Packages\Response;

use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpFoundation\Response;

class ResponseFormatter
{
    public static array $response = [];

    private static function setResponse(mixed $data = null, ?string $message = null, ?int $code = null, ?string $status = null, int $httpStatusCode = 200): array
    {
        self::$response['status'] = $status;
        self::$response['code'] = $code;
        self::$response['message'] = $message;
        self::$response['data'] = $data;

        return array_filter(self::$response);
    }

    public static function success(mixed $data = null, mixed $message = null, int $code = 200, int $httpStatusCode = 200): array
    {
        return self::setResponse($data, $message, $code, 'success', $httpStatusCode);
    }

    public static function entity(mixed $error = null): array
    {
        return static::error(
            data: $error,
            code: Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }

    public static function error(mixed $data = null, mixed $message = null, int $code = 400, int $httpStatusCode = 200): array
    {
        return self::setResponse($data, $message, $code, 'error', $httpStatusCode);
    }

    public static function methodNotAllowed(mixed $error = null, string $message = null): array
    {
        $message ??= __('error.method_not_allowed');

        return static::error(
            data: $error,
            message: $message,
            code: Response::HTTP_METHOD_NOT_ALLOWED
        );
    }

}

