<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



use App\Http\Controllers\api\StadiumController;

use App\Http\Controllers\api\PointTransactionController;


use App\Http\Controllers\api\AuthController;




use App\Http\Controllers\api\StadiumBookingController;

use App\Http\Controllers\api\BslotController;


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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});





Route::post('/customerinfo', [ApiController::class, 'customerinfo'])->name('customerinfo')->withoutMiddleware('throttle:api')->middleware('throttle:1000:1');
Route::post('/agentconnected', [ApiController::class, 'agentconnected'])->name('agentconnected')->withoutMiddleware('throttle:api')->middleware('throttle:1000:1');
Route::post('/sendresponselog', [ApiController::class, 'calllog'])->name('sendresponselog')->withoutMiddleware('throttle:api')->middleware('throttle:1000:1');

//app api
Route::post('/checkuser', [UserController::class,'checkUser']);
Route::post('/register', [UserController::class,'userRegister']);
Route::post('/userinfo', [UserController::class,'userInfo']);
Route::post('/validatelogin', [UserController::class,'validateLogin']);


Route::get('/bslots', [BslotController::class,'index']);

Route::apiResource('/stadiums', StadiumController::class);

Route::apiResource('/stadium-bookings', StadiumBookingController::class);

Route::apiResource('/point-transactions', PointTransactionController::class);



Route::post('/venue-login', [AuthController::class,'venueLogin']);

Route::get('/user-venue-details', [AuthController::class,'stadiumDetails']);

Route::post('/user-venue-bookings', [StadiumController::class,'createBooking']);


Route::get('/user-venue-bookings', [StadiumController::class,'bookings']);





