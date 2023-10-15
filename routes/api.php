<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AutenticacaoController;
use App\Http\Controllers\EmpresaController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('autenticacao')->group(function(){
    Route::post('/login', [AutenticacaoController::class, 'login'])->name('login');
    Route::post('/logout', [AutenticacaoController::class, 'logout'])->name('logout');
    Route::post('/refresh', [AutenticacaoController::class, 'refresh'])->name('refresh');
    Route::post('/me', [AutenticacaoController::class, 'me'])->name('me');
});

Route::prefix('empresa')->middleware('jwt.autenticacao')->group(function(){
    Route::get('/', [EmpresaController::class, 'index'])->name('empresa-index');
    Route::post('/cadastro', [EmpresaController::class, 'cadastro'])->name('empresa-cadastro');
    Route::delete('/deleta/{id}', [EmpresaController::class, 'deleta'])->name('empresa-deleta');
    Route::get('/busca/{id}', [EmpresaController::class, 'busca'])->name('empresa-busca');
    Route::put('/edita/{id}', [EmpresaController::class, 'edita'])->name('empresa-edita');
});

// Route::prefix('cadastro')->middleware('jwt.autenticacao')->group(function(){
//     Route::post('/funcionario');
// });