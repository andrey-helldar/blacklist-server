<?php

use Helldar\BlacklistCore\Constants\Server;
use Illuminate\Support\Facades\Route;

Route::namespace('\Helldar\BlacklistServer\Http\Controllers')
    ->prefix(Server::URI)
    ->middleware(Server::ROUTE_MIDDLEWARE)
    ->name(Server::ROUTE_PREFIX)
    ->group(function () {
        Route::post('/', 'IndexController@store')->name('store');
        Route::get('/', 'IndexController@check')->name('check');
        Route::get('/exists', 'IndexController@exists')->name('exists');
    });
