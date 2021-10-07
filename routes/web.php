<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\WebController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [WebController::class, 'index']);
Route::match(['get','post'],'/place-order', [WebController::class, 'placeOrder'])->name('placeOrder');
Route::post('/rave/callback', [WebController::class, 'callback'])->name('callback');
Route::post('/payment/callback', [WebController::class, 'handleGatewayCallback'])->name('payment');

Route::get('/buy', function () {
    return view('buy');
});
Route::get('/success', function () {
    return view('success');
});
Route::get('/failed', function () {
    return view('failed');
});

