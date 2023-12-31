<?php

use App\Http\Controllers\Controller;
use App\Http\Middleware\Core\PspScenario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group([
    'middleware' => [PspScenario::class]
], function () {
    Route::post('{any}', [Controller::class, 'index'])->where('any', '.*');
});

