<?php

use Illuminate\Support\Facades\Route;

Route::namespace('\Helldar\BlacklistServer\Http\Controllers')
    ->prefix('api/blacklist')
    ->middleware('api')
    ->name('api.blacklist.')
    ->group(function () {

        Route::post('/', 'IndexController@store')->name('store');
        Route::get('/', 'IndexController@check')->name('check');

    });
