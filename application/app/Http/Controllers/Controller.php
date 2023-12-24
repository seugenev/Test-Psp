<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\App;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function index(Request $request): Response
    {
        $psp = App::make($request->header('Test-Psp-ClassName'));
        $return = $psp->{$request->header('Test-Psp-MethodName')}();

        return $return instanceof Response ? $return : response()->noContent();
    }
}


