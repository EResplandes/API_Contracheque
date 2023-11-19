<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Mail\EnvioSenhaProvisoriaMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;


class FuncionarioService
{
    public function getAll()
    {

        return DB::table('funcionarios')
            ->join('empresas', 'empresas.id', '=', 'funcionarios.fk_empresa')
            ->select('funcionarios.id', 'funcionarios.nome_completo', 'funcionarios.cpf', 'funcionarios.tipo_usuario', 'funcionarios.email', 'empresas.nome_empresa')
            ->orderBy('funcionarios.id', 'asc')
            ->get();
    }

    public function cadastro($email, $cpf, $senha, $dados)
    {

        Mail::to($email)->send(new EnvioSenhaProvisoriaMail($cpf, $senha)); // Enviando o e-mail e pessando os parametros de cpf e senha

        DB::table('funcionarios')->insert($dados); // Inserindo no banco de dados

    }

    public function deleta($id)
    {

        DB::table('funcionarios')->where('id', $id)->delete(); // Deletando o funcionário de acordo com o id

    }

    public function desativa($id)
    {

        DB::table('funcionarios')->where('id', $id)->update(['tipo_usuario' => 'DESATIVADO']); // Atualizando o status do funcionário para desativado

    }

    public function busca($id)
    {

        return DB::table('funcionarios')
            ->join('empresas', 'empresas.id', '=', 'funcionarios.fk_empresa')
            ->select('funcionarios.id', 'funcionarios.nome_completo', 'funcionarios.cpf', 'funcionarios.tipo_usuario', 'funcionarios.email', 'empresas.nome_empresa')
            ->where('id', $id)
            ->get();
    }

    public function buscaAtivo()
    {

        return  DB::table('funcionarios')
            ->join('empresas', 'empresas.id', '=', 'funcionarios.fk_empresa')
            ->select('funcionarios.id', 'funcionarios.nome_completo', 'funcionarios.cpf', 'funcionarios.tipo_usuario', 'funcionarios.email', 'empresas.nome_empresa')
            ->where('tipo_usuario', 'ATIVO')->get(); // Busca funcionário com status de ativo

    }

    public function alteraSenha($id, $dados)
    {

        DB::table('funcionarios')->where('id', $id)->update(['password' => Hash::make($dados['senha_nova']), 'primeiro_acesso' => 0]); // Altera a senha do usuário

    }

    public function filtro($nome_completo, $cpf, $email, $fk_empresa)
    {

        $query = DB::table('funcionarios');

        // Aplicar filtros com base nos valores fornecidos
        if ($nome_completo) {
            $query->where('nome_completo', 'LIKE', '%' . $nome_completo . '%');
        }

        if ($cpf) {
            $query->where('cpf', $cpf);
        }

        if ($email) {
            $query->where('email', $email);
        }

        if ($fk_empresa) {
            $query->where('fk_empresa', $fk_empresa);
        }

        // Execute a consulta e obtenha os resultados
        return  $query->join('empresas', 'empresas.id', '=', 'funcionarios.fk_empresa')
            ->select('funcionarios.id', 'funcionarios.nome_completo', 'funcionarios.cpf', 'funcionarios.tipo_usuario', 'funcionarios.email', 'empresas.nome_empresa')->get();
    }

    public function edita($id, $dados)
    {

        DB::table('funcionarios')->where('id', $id)->update($dados); // Atualizando dados do funcionário no banco de dados de acordo com o di

    }
}
