<?php

use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;



/**
 * handles transaction notification callback from midtrans server
 */
Route::post("/v1/midtrans-payment-callback", [PaymentController::class, 'callbackHandler']);

/**
 * Ticket booking endpoint
 * Showing seat list and seats availability
 * Receiving seat booking request form user
 */
Route::get('/v1/ticketing/booking', [OrderController::class, 'reserveIndex']);
Route::post('/v1/ticketing/booking', [OrderController::class, 'reserveTicket']);

/**
 * Ticket order endpoint
 * Showing the detail (price) from the previous booked seat
 * Receiving user detail (name, email, phone number) from user request
 */
Route::get('/ticketing/order', [OrderController::class, 'orderIndex']);
Route::post('/ticketing/order', [PaymentController::class, 'orderTicket']);


