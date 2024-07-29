<?php

use App\Http\Controllers\Main;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::get('/', [Main::class, 'index'])->name('index');

//login routes
Route::get('/login', [Main::class, 'login'])->name('login');
Route::post('/login_submit', [Main::class, 'login_submit'])->name('login_submit');


Route::get('/main', [Main::class, 'main'])->name('main');
