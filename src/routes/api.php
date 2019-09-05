<?php

use Illuminate\Support\Facades\Route;

Route::prefix('api/spammer')
    ->middleware('api')
    ->name('api.spammer.')
    ->group(function () {

        Route::post('store', 'Helldar\SpammersServer\Http\Controllers\IndexController@store')->name('store');
        Route::get('exists', 'Helldar\SpammersServer\Http\Controllers\IndexController@exists')->name('exists');

    });
