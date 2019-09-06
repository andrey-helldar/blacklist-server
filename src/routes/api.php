<?php

use Illuminate\Support\Facades\Route;

Route::namespace('\Helldar\SpammersServer\Http\Controllers')
    ->prefix('api/spammer')
    ->middleware('api')
    ->name('api.spammer.')
    ->group(function () {

        Route::post('/', 'IndexController@store')->name('store');
        Route::get('/', 'IndexController@check')->name('check');

    });
