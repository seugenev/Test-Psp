<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Throwable;
use Symfony\Component\HttpFoundation\Response;


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

    public function render($request, Throwable $e): Response
    {
        $externalMessage = $e->getMessage();
        if (config('app.env') === 'production') {
            $externalMessage = 'something went wrong';
        }

        $loggerContent = [
            'message' => 'Unhandled exception',
            'trace' => [
                'message' => $e->getMessage() ?? '',
                'stack' => $e->getTraceAsString() ?? '',
                'uri' => $request->getRequestUri() ?? '',
                'headers' => $request->header() ?? '',
                'params' => json_encode($request->all(), JSON_PRETTY_PRINT),
                'method' => $request->method(),
            ]
        ];

        switch (true) {
            case $e instanceof ThrottleRequestsException:
                return $this->makeReturnContent($e->getMessage(), 'Too many requests', Response::HTTP_TOO_MANY_REQUESTS);

            case $e instanceof HttpResponseException:
                return $e->getResponse();

            case $e instanceof ApplicationException:
                logger()->error("Exceptions.Handler.render.ApplicationException", $loggerContent);
                return $this->makeReturnContent($externalMessage, 'Something went wrong', Response::HTTP_INTERNAL_SERVER_ERROR);

            default:
                logger()->error("Exceptions.Handler.render.default", $loggerContent);

                return $this->makeReturnContent(
                    $externalMessage,
                    'Internal error',
                    is_int($e->getCode()) ? $e->getCode() : Response::HTTP_INTERNAL_SERVER_ERROR,
                );
        }
    }

    /**
     * @param string|array $message
     * @param string $status
     * @param int $code
     * @return JsonResponse
     */
    private function makeReturnContent(
        string|array $message,
        string $status,
        int $code = Response::HTTP_INTERNAL_SERVER_ERROR
    ): JsonResponse
    {
        $content = [
            'error' => [
                'message' => $message,
                'status' => $status,
            ],
        ];

        return response()->json($content, $code === 0 ? Response::HTTP_INTERNAL_SERVER_ERROR : $code);
    }
}
