<?php

use App\Http\Controllers\CounterController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/counter', [CounterController::class, 'getCount']);