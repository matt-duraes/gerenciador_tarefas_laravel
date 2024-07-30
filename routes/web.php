<?php

use App\Http\Controllers\Main;
use App\Http\Middleware\CheckLogin;
use App\Http\Middleware\CheckLogout;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

// in app
Route::middleware([CheckLogin::class])->group(function() {
    Route::get('/', [Main::class, 'index'])->name('index');
    Route::get('/logout', [Main::class, 'logout'])->name('logout');
});

// out app
Route::middleware([CheckLogout::class])->group(function() {
    Route::get('/login', [Main::class, 'login'])->name('login');
    Route::post('/login_submit', [Main::class, 'login_submit'])->name('login_submit');
});
