<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class TestPspHeaderException extends Exception
{
    protected $message = "You must send the 'Test-Psp' key in the request header";

    public function render(): JsonResponse
    {
        return response()->json([
            'message' => $this->message,
        ], Response::HTTP_BAD_REQUEST);
    }
}
