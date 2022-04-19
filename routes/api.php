<?php

use Illuminate\Support\Facades\Route;

Route::post('login', 'App\Http\Controllers\UserController@login');

Route::group(['middleware' => ['jwt.verify']], function () {

    Route::post('logout', 'App\Http\Controllers\UserController@logout');
});
