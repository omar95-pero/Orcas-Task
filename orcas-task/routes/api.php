<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


    Route::post('login', [\App\Http\Controllers\Api\AuthController::class, 'authenticate']);
    Route::post('register', [\App\Http\Controllers\Api\AuthController::class, 'register']);

    Route::group(['middleware' => ['jwt.verify']], function() {
        Route::get('logout', [\App\Http\Controllers\Api\AuthController::class, 'logout']);
        Route::get('get_profile', [\App\Http\Controllers\Api\AuthController::class, 'get_user']);
        Route::get('get_users', [\App\Http\Controllers\Users\FetchDataController::class, 'getAllUsers']);
        Route::get('search_user/{statment}', [\App\Http\Controllers\Users\FetchDataController::class, 'search']);

    });
