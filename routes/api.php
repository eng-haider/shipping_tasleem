<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OldController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\VersionController;
use App\Http\Controllers\GovernorateController;
use App\Http\Controllers\OrderReportController;
use App\Http\Controllers\DriverDocumentController;

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

//add middleware rate limmiter 3 evry 2hour

Route::get('city', [OldController::class, 'getCities']);
Route::get('public/version/{version}', [VersionController::class, 'getPublicVersion']);
Route::group([
    'prefix' => 'auth'
], function () {
    Route::middleware('throttle:30,120')->group(function () {
        Route::post('/send-otp', [AuthController::class, 'SendOtp']);
    });
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::put('/profile', [AuthController::class, 'profile']);    
    Route::get('/details', [AuthController::class, 'details']);    
    Route::get('/permissions', [AuthController::class, 'userPermissions']);    
});
Route::group([
    'middleware' => 'user-auth',
], function () {
    //Single API Route   
    Route::get('export-order-excel', [OrderController::class, 'exportOrderExcil']);
    Route::put('active-or-deactive/user/{user}', [UserController::class, 'activeDeactiveUser']);
    Route::post('/assign-permission-to/role/{id}', [RoleController::class, 'assignPermissionToRole']);    
    Route::post('/add/role/user/{id}', [RoleController::class, 'assignRoleToUser']);    
    Route::put('/change-status/order/{uuid}', [OrderController::class, 'changeStatus']); 
    Route::post('/user-company', [UserController::class, 'userCompany']); 
    Route::post('/governorate-to-company', [GovernorateController::class, 'CreateGovernorateToCompany']); 
    Route::get('/report/order-status', [OrderReportController::class, 'getOrderStatus']); 
    Route::get('/report/order-driver', [OrderReportController::class, 'getOrderDriver']); 
    Route::get('/report/order-company', [OrderReportController::class, 'getOrderCompany']); 
    Route::get('/report/order-governorate', [OrderReportController::class, 'getOrderGovernorate']);
    Route::put('/company/{id}/restore', [CompanyController::class, 'restore']); 
    Route::get('/destroyed/company', [CompanyController::class, 'getDestroyedCompanyList']); 
    Route::put('/verified/driver/{driver}', [DriverController::class, 'verifiedDriver']); 
    Route::put('/block/driver/{driver}', [DriverController::class, 'blockedDriver']); 
    Route::put('/driverDocument/{driverDocument}/status-change', [DriverDocumentController::class, 'changeStatus']); 

    //Resources API Routes 
    Route::apiResource('user', 'App\Http\Controllers\UserController');
    Route::apiResource('role', 'App\Http\Controllers\RoleController');
    Route::apiResource('permission', 'App\Http\Controllers\PermissionController');
    Route::apiResource('governorate', 'App\Http\Controllers\GovernorateController');
    Route::apiResource('company', 'App\Http\Controllers\CompanyController');
    Route::apiResource('driver', 'App\Http\Controllers\DriverController');
    Route::apiResource('order', 'App\Http\Controllers\OrderController')->except(['store']);
    Route::apiResource('status', 'App\Http\Controllers\StatusController');
    Route::apiResource('version', 'App\Http\Controllers\VersionController');
    Route::apiResource('notification', 'App\Http\Controllers\NotificationController')->except(['update', 'destroy']);
    Route::apiResource('driverDocument', 'App\Http\Controllers\DriverDocumentController');
    Route::apiResource('document', 'App\Http\Controllers\DocumentController');
    Route::apiResource('customer', 'App\Http\Controllers\CustomerController');
});
//include company routes
include('company.php');
//include driver routes
include('driver.php');
