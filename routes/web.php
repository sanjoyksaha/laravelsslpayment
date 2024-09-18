<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;

Route::get('/', function () {
    return view('welcome');
});


Route::post('payment/initiate', [PaymentController::class, 'initiatePayment'])->name('payment.initiate');
Route::post('payment/success', [PaymentController::class, 'paymentSuccess'])->name('payment.success');
Route::post('payment/failure', [PaymentController::class, 'paymentFail'])->name('payment.failure');
Route::post('payment/cancel', [PaymentController::class, 'paymentCancel'])->name('payment.cancel');
Route::post('payment/ipn_listen', [PaymentController::class, 'IPN'])->name('payment.ipn');

//Route::post('sslcommerz/success','PaymentController@paymentSuccess')->name('payment.success');
//Route::post('sslcommerz/failure','PaymentController@paymentFail')->name('failure');
//Route::post('sslcommerz/cancel','PaymentController@paymentCancel')->name('cancel');
//Route::post('sslcommerz/ipn','PaymentController@IPN')->name('payment.ipn');
