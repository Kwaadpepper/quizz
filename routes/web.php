<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes([
    'reset' => false,
    'verify' => false,
    'register' => false
]);

Route::view('/', 'home')->middleware('auth')->name('home');
