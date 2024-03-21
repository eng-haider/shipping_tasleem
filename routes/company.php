<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Company\OrderReportController;
use App\Http\Controllers\Company\AuthController as CompanyAuthController;
use App\Http\Controllers\Company\CompanyController as MyCompanyController;
use App\Http\Controllers\Company\OrderController as CompanyOrderController;
use App\Http\Controllers\Company\DriverController as CompanyDriverController;
use App\Http\Controllers\Company\StatusController as CompanyStatusController;
use App\Http\Controllers\Company\GovernorateController as MyGovernorateController;

/*
|--------------------------------------------------------------------------
| Company API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group([
    'prefix' => 'company/v1/auth'
], function () {

    Route::middleware('throttle:30,120')->group(function () {
        Route::post('/send-otp',[CompanyAuthController::class, 'SendOtp']);
    });
    Route::post('/login', [CompanyAuthController::class, 'login']);
    Route::post('/logout', [CompanyAuthController::class, 'logout']);
    Route::put('/profile', [CompanyAuthController::class, 'profile']);    
    Route::get('/details', [CompanyAuthController::class, 'details']);       
});
Route::group([
    'middleware' => 'company-auth',
    'prefix' => 'company/v1'
], function () {
    //Single API Routes
    Route::get('/order', [CompanyOrderController::class, 'index']); 
    Route::get('/order/{order}', [CompanyOrderController::class, 'show']); 
    Route::put('/change-status/order/{uuid}', [CompanyOrderController::class, 'changeStatus']); 
    Route::put('/change-statu-multiple/order', [CompanyOrderController::class, 'changeStatusmultiple']); 
    Route::get('/my-company', [MyCompanyController::class, 'show']); 
    Route::put('/my-company', [MyCompanyController::class, 'update']); 
    Route::get('/governorate', [MyGovernorateController::class, 'index']); 
    Route::get('/governorate/{governorate}', [MyGovernorateController::class, 'show']); 
    Route::get('/status', [CompanyStatusController::class, 'index']); 
    Route::get('/status/{status}', [CompanyStatusController::class, 'show']);
    Route::get('/driver', [CompanyDriverController::class, 'index']); 
    Route::post('/driver', [CompanyDriverController::class, 'store']); 
    Route::get('/driver/{id}', [CompanyDriverController::class, 'show']); 
    Route::get('/driver', [CompanyDriverController::class, 'index']); 
    Route::put('/driver/{id}', [CompanyDriverController::class, 'update']); 
    Route::delete('/driver/{id}', [CompanyDriverController::class, 'destroy']); 
    Route::delete('order/{order}/company', [CompanyOrderController::class, 'destroy']); 
    Route::apiResource('driverVehicle', 'App\Http\Controllers\Company\DriverVehicleController');
    Route::apiResource('employee', 'App\Http\Controllers\Company\EmployeeController')->except(['delete']); 
    Route::get('/report/order-status', [OrderReportController::class, 'getOrderStatus']); 
    Route::get('/report/order-driver', [OrderReportController::class, 'getOrderDriver']); 
    Route::get('/report/order-company', [OrderReportController::class, 'getOrderCompany']); 
    Route::get('/report/order-governorate', [OrderReportController::class, 'getOrderGovernorate']); 
});