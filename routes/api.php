<?php
use App\Http\Controllers\UserController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\RequestPaymentController;
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

//Route::middleware('auth:api')->get('/user', UserController::user);
Route::post('/users/login',[UserController::class, 'login']);
Route::post('/users/password-reset',[UserController::class, 'passwordReset']);//da li ovde treba /user/password-reset
Route::delete('/users/logout',[UserController::class, 'logout']);
Route::get('/users/info',[UserController::class, 'user']);
Route::resource('users', UserController::class);
Route::resource('payments', PaymentController::class);
Route::resource('request-payments', RequestPaymentController::class);
Route::resource('contacts', ContactController::class);
