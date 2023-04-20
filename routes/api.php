<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ParcelController;

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
    Route::post('/login', [AuthController::class, 'login'])->name('api.login');

    Route::middleware(['jwt.verify'])->group(function () {
        Route::middleware(['sender.verify'])->group(function () {
            Route::post('/createParcel', [ParcelController::class, 'createParcel'])->name('api.create.parcel');
            Route::get('/sender/parcels', [ParcelController::class, 'getSenderParcels'])->name('api.sender.parcels');
        });

        Route::middleware(['biker.verify'])->group(function () {
            Route::get('/biker/parcels', [ParcelController::class, 'getBikerParcels'])->name('api.biker.parcels');
            Route::put('/parcel/picked', [ParcelController::class, 'parcelPicked'])->name('api.parcel.picked');
            Route::put('/parcel/delivered', [ParcelController::class, 'parcelDelivered'])->name('api.parcel.delivered');
        });

        Route::post('/logout', [AuthController::class, 'logout'])->name('api.logout');
    });
});
