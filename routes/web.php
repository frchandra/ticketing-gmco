<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OwnerController;
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
Route::get('/token', [OrderController::class, 'showToken']);

Route::get('/reserve', [OrderController::class, 'reserveIndex']);
Route::post('/reserve', [OrderController::class, 'reserveTicket']);
Route::get('/order', [OrderController::class, 'orderIndex']);
Route::post('/order', [OrderController::class, 'orderTicket']);
Route::get('/login-admin', [AuthController::class, 'indexLogin']);
Route::post('/login-admin', [AuthController::class, 'login']);


Route::middleware(['auth'])->group(function (){
    Route::post('/create-admin', [AuthController::class, 'register']);
    Route::get('/logout-admin', [AuthController::class, 'logout']);

    Route::get('/resolve', [ResolveController::class, 'index']);
    Route::get('/tf_proof/{path}', [ResolveController::class, 'showTf']);
    Route::post('/confirm', [ResolveController::class, 'confirmOrder']);

    Route::get('/sold', [OwnerController::class, 'index']);

    Route::get('/owner', [OwnerController::class, 'index']);
    Route::get('/attend/{name}', [OwnerController::class, 'setAttend']);
});

Route::middleware(['checkout'])->group(function (){
    Route::get('/seat-info/{name}', [OwnerController::class, 'seatInfo']);
});


