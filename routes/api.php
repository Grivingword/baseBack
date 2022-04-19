<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::post('login', [UserController::class, 'login']);

Route::group(['middleware' => ['jwt.verify']], function () {

    Route::post('logout', [UserController::class, 'logout']);
});
