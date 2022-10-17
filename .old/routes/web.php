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
*/

Route::get('/', function () {
    return view('welcome');
})->name('home');


Route::get('/token', function () {
    return csrf_token();
});

/**
 * Ticket booking endpoint
 * Showing seat list and seats availability
 * Receiving seat booking request form user
 */
Route::get('/ticketing/booking', [OrderController::class, 'reserveIndex']);
Route::post('/ticketing/booking', [OrderController::class, 'reserveTicket']);

/**
 * Ticket order endpoint
 * Showing the detail (price) from the previous booked seat
 * Receiving user detail (name, email, phone number) from user request
 */
Route::get('/ticketing/order', [OrderController::class, 'orderIndex']);
Route::post('/ticketing/order', [PaymentController::class, 'orderTicket']);

/**
 * Handle admin login activity
 */
Route::get('/admin/login', [AuthController::class, 'indexLogin']);
Route::post('/admin/login', [AuthController::class, 'login']);

/*
 * Only admin can access this endpoint
 */
Route::middleware(['auth'])->group(function (){
    /**
     * Handle admin register and logout activity
     */
    Route::post('/admin/create-admin', [AuthController::class, 'register']);
    Route::get('/admin/logout', [AuthController::class, 'logout']);
    /**
     * See unresolved ticket transaction form user
     */
    Route::get('/admin/order-list', [ResolveController::class, 'index']);
    /**
     * See the successful transaction from user, see the seat-ticket ownership
     */
    Route::get('/admin/confirmed-order', [OwnerController::class, 'index']);
    /**
     * This endpoint is handling qr code scanning by admin user
     */
    Route::get('/authenticate/{unique}', [OwnerController::class, 'indexSetAttend']);
    /**
     * Set the user attendance status (this usually done on d-day open gate or on ticket exchange event)
     * -notExchanged : the user have not exchanged the e-ticket with the physical ticket
     * -exchangedNotAttend : the user has exchanged the ticket but not/haven't present in the concert
     * -exchangedModified : the user is present in the concert but intentionally set as not present
     */
    Route::post('/attend/{unique}', [OwnerController::class, 'setAttend']);
});

/**
 * This endpoint is handling qr code scanning by normal user
 */
Route::middleware(['checkout'])->group(function (){
    Route::get('/seat-info/{unique}', [OwnerController::class, 'seatInfo']);
});


