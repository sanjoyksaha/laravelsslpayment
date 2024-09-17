<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


//Route::get('/example1', [\App\Http\Controllers\Payment::class, 'exampleEasyCheckout']);
//Route::get('/example2', [\App\Http\Controllers\Payment::class, 'exampleHostedCheckout']);

Route::post('/pay', [\App\Http\Controllers\Payment::class, 'index']);
//Route::post('/pay-via-ajax', [\App\Http\Controllers\Payment::class, 'payViaAjax']);

Route::post('/payment/success', [\App\Http\Controllers\Payment::class, 'success']);
Route::post('/payment/fail', [\App\Http\Controllers\Payment::class, 'fail']);
Route::post('/payment/cancel', [\App\Http\Controllers\Payment::class, 'cancel']);

Route::post('/payment/ipn_listen', [\App\Http\Controllers\Payment::class, 'ipn']);
