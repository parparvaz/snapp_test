<?php

namespace App\Exceptions;

use App\Packages\Response\ResponseFormatter;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
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
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $e): Response|JsonResponse|\Symfony\Component\HttpFoundation\Response
    {
        if ($e instanceof NotFoundHttpException) {
            $response = ResponseFormatter::error(message: __('trans.url.not_found'));

            return response()->json($response, $response['code']);
        }

        if ($e instanceof ModelNotFoundException) {
            $response = ResponseFormatter::methodNotAllowed(message: __('error.method_not_allowed'));

            return response()->json($response, $response['code']);
        }

        if ($e instanceof MethodNotAllowedException) {
            $response = ResponseFormatter::methodNotAllowed();

            return response()->json($response, $response['code']);
        }

        $response = ResponseFormatter::error(message: $e->getMessage());

        return response()->json($response, $response['code']);

    }
}
