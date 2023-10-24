<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AutenticacaoController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\FuncionarioController;
use App\Http\Controllers\ContrachequeController;

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
    Route::post('/filtro', [EmpresaController::class, 'filtro'])->name('empresa-filtro');
});

Route::prefix('funcionario')->middleware('jwt.autenticacao')->group(function(){
    Route::get('/', [FuncionarioController::class, 'index'])->name('funcionario-index');
    Route::post('/cadastro', [FuncionarioController::class, 'cadastro'])->name('funcionario-cadastro');
    Route::delete('/deleta/{id}', [FuncionarioController::class, 'deleta'])->name('funcionario-deleta');
    Route::put('/desativa/{id}', [FuncionarioController::class, 'desativa'])->name('funcionario-desativa');
    Route::get('/busca/{id}', [FuncionarioController::class, 'busca'])->name('funcionario-busca');
    Route::put('/edita/{id}', [FuncionarioController::class, 'edita'])->name('funcionario-edita');
});

Route::prefix('contracheque')->middleware('jwt.autenticacao')->group(function(){
    Route::get('/', [ContrachequeController::class, 'index'])->name('contracheque-index');
    Route::get('/busca/{id}', [ContrachequeController::class, 'busca'])->name('contracheque-busca');
    Route::post('/cadastro', [ContrachequeController::class, 'cadastro'])->name('contracheque-cadsatro');
});