<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AutenticacaoController extends Controller
{
    public function login(Request $request){

        $credenciais = $request->only('cpf', 'password'); // Pegando dados vindo do request
        
        $token = auth('api')->attempt($credenciais); // Tentando conexão com dados vindos do request

        if($token) {

            return response()->json(['Token:' => $token], 422);

        } else {

            return response()->json(['Erro:' => 'Usuário ou senha inválidos!'], 400);

        }

        dd($token);

    }

    public function logout(){
        return 'teste';

    }

    public function refresh(){
        return 'teste';

    }

    public function me(){
        return 'teste';

    }
}
