<?php

use App\Http\Controllers\Order\OrderController;
use App\Http\Controllers\User\UserController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1'], function () {
    Route::get('users', [UserController::class, 'index']);
    Route::get('orders', [OrderController::class, 'index']);
});
