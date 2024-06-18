<?php

use App\Models\Counter;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    Counter::incrementCount('home');
    return view('welcome');
})->name('home');

Route::get('/counter/{routeName}', 'CounterController@getCount');