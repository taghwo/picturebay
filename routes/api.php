<?php

use App\Http\Controllers\Api\PhotographRequestController;
use App\Http\Controllers\Api\PhotoShootController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
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

Route::prefix('v1')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('login', [AuthenticatedSessionController::class,'login']);
        Route::get('me', [AuthenticatedSessionController::class,'currentuser'])->middleware('auth:sanctum');
        Route::post('logout', [AuthenticatedSessionController::class,'logout'])->middleware('auth:sanctum');
        Route::post('register', [RegisteredUserController::class,'store']);
    });
    Route::ApiResource('products', ProductController::class)->middleware('auth:sanctum');
    Route::resource('photoshoots', PhotoShootController::class)->middleware('auth:sanctum');
    Route::post('photoshoots/{id}/status', [PhotoShootController::class,'updatePhotoStatus'])->middleware('auth:sanctum');
    Route::get('photoshoots/{id}/download', [PhotoShootController::class,'downloadHQFile'])->middleware('auth:sanctum');
    Route::get('photographrequests/me', [PhotographRequestController::class,'myrequest'])->middleware('auth:sanctum');
    Route::resource('photographrequests', PhotographRequestController::class)->middleware('auth:sanctum');
    Route::get('photographers', [UserController::class,'photographers'])->middleware('auth:sanctum');
    Route::get('buyers', [UserController::class,'buyers'])->middleware('auth:sanctum');
});