<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\V1\RestaurantController;
use App\Http\Controllers\V1\DishController;
use \App\Http\Controllers\V1\OrderController;

Route::group(['prefix' => 'v1'], function () {
    Route::group(['prefix' => 'restaurants'], function () {
        Route::get('/', [RestaurantController::class, 'index']);
    });

    Route::group(['prefix' => 'dishes'], function () {
        Route::get('/', [DishController::class, 'index']);
    });

    Route::group(['prefix' => 'orders'], function () {
        Route::get('/', [OrderController::class, 'index']);
        Route::post('/', [OrderController::class, 'store']);
    });
});
