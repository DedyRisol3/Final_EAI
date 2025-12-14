<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


Route::get('/', function () {
    return Inertia::render('Auth/Login');
});

Route::get('/loyalty/sso/callback', function () {
    return Inertia::render('Auth/SsoCallback');
})->name('loyalty.sso.callback');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
});
