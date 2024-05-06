<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class AutenticacaoController extends Controller
{

    public function login(Request $request)
    {

        $credenciais = $request->only('cpf', 'password'); // Pegando dados vindo do request

        $token = auth('api')->attempt($credenciais); // Tentando conexão com dados vindos do request

        if ($token) {

            $cpf = $request->input('cpf'); // Pegando o CPF do request

            $dados = DB::table('funcionarios')
                ->join('empresas', 'empresas.id', '=', 'funcionarios.fk_empresa')
                ->select('funcionarios.id', 'funcionarios.nome_completo', 'funcionarios.email', 'funcionarios.cpf', 'funcionarios.tipo_usuario', 'funcionarios.fk_empresa', 'funcionarios.primeiro_acesso', 'empresas.nome_empresa')
                ->where('cpf', $cpf)
                ->get(); // Pegando os dados do usuário de acordo com o CPF

            return response()->json(['resposta' => 'Autenticação realizada com sucesso!' ,'token' => $token, 'informacoes' => $dados], 200); // Retornando os dados e o token

        } else {

            return response()->json(['Erro:' => 'Usuário ou senha inválidos!'], 401); // Retornando responsa de erro no usuário

        }
    }

    public function logout()
    {

        auth('api')->logout(); // Invalidando o token

        return response()->json(['resposta' => 'Usuários deslogado com sucesso!']); // Retornando a resposta de deslogado com sucesso!

    }
}
