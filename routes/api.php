<?php

use App\Http\Controllers\Api\AuthContoller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ServiceController;

Route::post('login', [AuthContoller::class, 'login']);

Route::group(['middleware' => ['auth:api']], function () {
    Route::get('user', [AuthContoller::class, 'user']);
});