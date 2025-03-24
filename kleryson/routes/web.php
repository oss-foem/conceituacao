<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('login');
});

Route::post('/logar', ['App\Http\Controllers\Usuario', 'Logar']);
Route::get('/logado', ['App\Http\Controllers\Logado', 'Logado']);
Route::get('/logout', ['App\Http\Controllers\Usuario', 'Logout']);

Route::get('/usuario', ['App\Http\Controllers\Usuario', 'Listagem']);
Route::get('/usuarioCadastro', ['App\Http\Controllers\Usuario', 'Cadastro']);
Route::get('/usuarioExcluir/{id}', ['App\Http\Controllers\Usuario', 'Excluir']);
Route::post('/usuarioSalvar', ['App\Http\Controllers\Usuario', 'Salvar']);

Route::get('/perfil', ['App\Http\Controllers\Perfil', 'Listagem']);
