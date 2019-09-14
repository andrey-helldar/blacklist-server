<?php

use Helldar\BlacklistCore\Constants\Server;
use Illuminate\Support\Facades\Route;

Route::namespace('\Helldar\BlacklistServer\Http\Controllers')
    ->prefix(Server::URI)
    ->middleware('api')
    ->name('api.blacklist.')
    ->group(function () {

        Route::post('/', 'IndexController@store')->name('store');
        Route::get('/', 'IndexController@exists')->name('exists');

    });
