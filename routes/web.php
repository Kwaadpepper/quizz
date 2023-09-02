<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes([
    'reset' => false,
    'verify' => false,
    'register' => false
]);

Route::view('{anyExceptRoot}', 'home')->middleware('auth')
    ->where('anyExceptRoot', '[^/]*')->name('home');
