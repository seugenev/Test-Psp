<?php

namespace App\Http\Middleware\Core;

use App\Exceptions\TestPspHeaderException;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateTestPspHeader
{
    /**
     * Check if exists Test-Psp header required for this service
     * and if PSP service mentioned in it exists among modules
     *
     * @param Request $request
     * @param Closure(Request): (Response) $next
     * @return Response
     * @throws Exception
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->headers->has('Test-Psp') || empty($request->headers->get('Test-Psp'))) {
            throw new TestPspHeaderException();
        }

        return $next($request);
    }
}
