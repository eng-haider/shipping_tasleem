<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Driver\AuthController;
use App\Http\Controllers\Driver\OrderController;
use App\Http\Controllers\Driver\StatusController;
use App\Http\Controllers\Driver\GovernorateController;
use App\Http\Controllers\Driver\OrderReportController;
use App\Http\Controllers\Driver\NotificationController;
use App\Http\Controllers\Driver\DriverVehicleController;
use App\Http\Controllers\Driver\DriverDocumentController;

/*
|--------------------------------------------------------------------------
| Driver API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group([
    'prefix' => 'driver/auth'
], function () {
    Route::middleware('throttle:30,120')->group(function () {
        Route::post('/send-otp', [AuthController::class, 'SendOtp']);
    });
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/logout', [AuthController::class, 'logout']);
    Route::put('/profile', [AuthController::class, 'profile']);    
    Route::get('/details', [AuthController::class, 'details']);   
});
Route::group([
    'middleware' => 'driver-auth',
    'prefix' => 'driver/v1'
], function () {
    //Single API Route
    
    Route::get('/order-report', [OrderReportController::class, 'getOrderStatus']); 
    Route::get('/order', [OrderController::class, 'index']); 
    Route::get('/order/{tr}', [OrderController::class, 'show']); 
    Route::post('/order', [OrderController::class, 'store']); 
    Route::put('/reassign/order/{tr}', [OrderController::class, 'reassignOrder']); 
    Route::put('/change-status/order/{tr}', [OrderController::class, 'changeStatus']); 
    Route::get('/governorate', [GovernorateController::class, 'index']); 
    Route::get('/governorate/{governorate}', [GovernorateController::class, 'show']);  
    Route::get('/status', [StatusController::class, 'index']); 
    Route::get('/status/{status}', [StatusController::class, 'show']);
    Route::get('/my-document/{id}', [DriverDocumentController::class, 'show']); 
    Route::get('/get-documents', [DriverDocumentController::class, 'getDocument']);  
    Route::post('/driverDocument/driver', [DriverDocumentController::class, 'store']); 
    Route::put('/driverDocument/{driverDocument}/driver', [DriverDocumentController::class, 'update']); 
    Route::get('/driverVehicle/{driverVehicle}/driver', [DriverVehicleController::class, 'show']); 
    Route::post('/driverVehicle/driver', [DriverVehicleController::class, 'store']); 
    Route::put('/driverVehicle/{driverVehicle}/driver', [DriverVehicleController::class, 'update']); 
    Route::put('/order/{tr}/add-rate', [OrderController::class, 'addRate']); 
    Route::get('my/notification', [NotificationController::class, 'index']); 
    Route::put('/seen/notification', [NotificationController::class, 'seenNotifications']); 
});