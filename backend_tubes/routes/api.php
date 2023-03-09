<?php

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('register', 'Api\AuthController@register');
Route::post('login', 'Api\AuthController@login');

// Verify email
Route::get('/email/verify/{id}/{hash}', 'VerifyEmailController@__invoke')
    ->middleware(['signed', 'throttle:6,1'])
    ->name('verification.verify');

// Resend link to verify email
Route::post('/email/verify/resend', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Verification link sent!');
})->middleware(['auth:api', 'throttle:6,1'])->name('verification.send');



Route::group(['middleware' => 'auth:api'], function(){
    Route::get('user/{id}', 'Api\AuthController@show');
    Route::get('logout', 'Api\AuthController@logout');
    Route::post('update/{id}', 'Api\AuthController@update');

    Route::get('reservasi', 'Api\ReservasiController@index');
    Route::get('reservasi/{id}', 'Api\ReservasiController@show');
    Route::post('reservasi', 'Api\ReservasiController@store');
    Route::put('reservasi/{id}', 'Api\ReservasiController@update');
    Route::put('reservasi/changeStatus/{id}', 'Api\ReservasiController@changeStatus');
    Route::delete('reservasi/{id}', 'Api\ReservasiController@destroy');

    Route::get('layanan', 'Api\LayananController@index');
    Route::get('layanan/{id}', 'Api\LayananController@show');
    Route::post('layanan', 'Api\LayananController@store');
    Route::put('layanan/{id}', 'Api\LayananController@update');
    Route::delete('layanan/{id}', 'Api\LayananController@destroy');

    Route::get('produk', 'Api\ProdukController@index');
    Route::get('produk/{id}', 'Api\ProdukController@show');
    Route::post('produk', 'Api\ProdukController@store');
    Route::post('produk/{id}', 'Api\ProdukController@update');
    Route::delete('produk/{id}', 'Api\ProdukController@destroy');

});