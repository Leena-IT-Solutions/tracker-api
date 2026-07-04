<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified', 'admin'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth', 'admin'])
    ->name('profile');

Route::view('users', 'users')
    ->middleware(['auth', 'verified', 'admin'])
    ->name('users.index');

require __DIR__.'/auth.php';
