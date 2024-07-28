<?php

use App\Http\Controllers\Main;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    try {
        DB::Connection()->getPdo();
        echo "Conexão efetuada com sucesso " . DB::connection()->getDatabaseName();        
    } catch (\Exception $e) {
        die('Não foi possível ligar à base de dados '. $e->getMessage());
    }
    echo " Gestor de tarefas";
});
Route::get('/main', [Main::class, 'index']);
Route::get('/users', [Main::class, 'users']);

Route::get('/view', [Main::class, 'view']);

