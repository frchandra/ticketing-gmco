<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ResolveController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|user_email
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->name('home');


Route::get('/ticketing/booking', [OrderController::class, 'reserveIndex']);
Route::post('/ticketing/booking', [OrderController::class, 'reserveTicket']);
Route::get('/ticketing/order', [OrderController::class, 'orderIndex']);
Route::post('/ticketing/order', [PaymentController::class, 'orderTicket']);
Route::get('/admin/login', [AuthController::class, 'indexLogin']);
Route::post('/admin/login', [AuthController::class, 'login']);




Route::middleware(['auth'])->group(function (){
    Route::post('/admin/create-admin', [AuthController::class, 'register']);
    Route::get('/admin/logout', [AuthController::class, 'logout']);
    Route::get('/admin/order-list', [ResolveController::class, 'index']);
    Route::get('/admin/confirmed-order', [OwnerController::class, 'index']);

    Route::get('/authenticate/{unique}', [OwnerController::class, 'indexSetAttend']);
    Route::post('/attend/{unique}', [OwnerController::class, 'setAttend']);

/*    Route::post('/not-attend/{name}', [OwnerController::class, 'setNotAttend']); //todo this should be use param
    Route::post('/get-ticket/{unique}', [OwnerController::class, 'setGetTicket']);
    Route::post('/not-get-ticket/{name}', [OwnerController::class, 'setNotGetTicket']);*/
});

Route::middleware(['checkout'])->group(function (){
    Route::get('/seat-info/{unique}', [OwnerController::class, 'seatInfo']);
});


