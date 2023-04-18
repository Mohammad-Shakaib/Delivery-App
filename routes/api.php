<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
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

// Define a route group with a prefix, middleware, and namespace
Route::group([], function () {
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware(['jwt.verify', 'sender.verify'])->group(function () {
        Route::post('/createParcel', [AuthController::class, 'createParcel']);
        Route::get('/sender/parcels', [AuthController::class, 'getSenderParcels']);
    });

    Route::middleware(['jwt.verify', 'biker.verify'])->group(function () {
        Route::get('/all/parcels', [AuthController::class, 'getParcels']);
        Route::put('/parcel/picked', [AuthController::class, 'pickParcel']);
        Route::put('/parcel/delivered', [AuthController::class, 'parcelDelivered']);
    });
});


