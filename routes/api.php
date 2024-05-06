<?php

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

Route::prefix('empresa')->group(function(){
    Route::get('/', [EmpresaController::class, 'index'])->name('empresa-index');
    Route::post('/cadastro', [EmpresaController::class, 'cadastro'])->name('empresa-cadastro');
    Route::delete('/deleta/{id}', [EmpresaController::class, 'deleta'])->middleware('verify.id')->name('empresa-deleta');
    Route::get('/busca/{id}', [EmpresaController::class, 'busca'])->middleware('verify.id')->name('empresa-busca');
    Route::put('/edita/{id}', [EmpresaController::class, 'edita'])->middleware('verify.id')->name('empresa-edita');
    Route::post('/filtro', [EmpresaController::class, 'filtro'])->name('empresa-filtro');
});

Route::prefix('funcionario')->group(function(){
    Route::get('/', [FuncionarioController::class, 'index'])->name('funcionario-index');
    Route::get('/ativo',[FuncionarioController::class, 'buscaAtivo'])->name('funcionario-ativo');
    Route::post('/cadastro', [FuncionarioController::class, 'cadastro'])->name('funcionario-cadastro');
    Route::delete('/deleta/{id}', [FuncionarioController::class, 'deleta'])->middleware('verify.id')->name('funcionario-deleta');
    Route::put('/desativa/{id}', [FuncionarioController::class, 'desativa'])->middleware('verify.id')->name('funcionario-desativa');
    Route::get('/busca/{id}', [FuncionarioController::class, 'busca'])->middleware('verify.id')->name('funcionario-busca');
    Route::put('/edita/{id}', [FuncionarioController::class, 'edita'])->middleware('verify.id')->name('funcionario-edita');
    Route::post('/filtro', [FuncionarioController::class, 'filtro'])->name('funcionario-filtro');
    Route::post('/alteraSenha/{id}', [FuncionarioController::class, 'alteraSenha'])->middleware('verify.id')->name('funcionario-altera');
});

Route::prefix('contracheque')->group(function(){
    Route::get('/', [ContrachequeController::class, 'index'])->name('contracheque-index');
    Route::get('/busca/{id}', [ContrachequeController::class, 'busca'])->middleware('verify.id')->name('contracheque-busca');
    Route::get('/buscaContracheque/{id}', [ContrachequeController::class, 'buscaContracheque'])->middleware('verify.id')->name('buscaContracheque-busca');
    Route::post('/cadastro', [ContrachequeController::class, 'cadastro'])->name('contracheque-cadastro');
    Route::get('/pendencias', [ContrachequeController::class, 'buscaPendencias'])->name('contracheque-pendencias');
    Route::get('/total', [ContrachequeController::class, 'totalPendencias'])->name('contracheque-total');
    Route::get('/atualizaStatus/{id}', [ContrachequeController::class, 'atualizaStatus'])->middleware('verify.id')->name('contracheque-atualizaStatus');
});
