<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class EmpresaService
{
    public function getAll()
    {
        return DB::table('empresas')->orderBy('id', 'asc')->get();
    }

    public function cadastrarEmpresa($nome)
    {
        $dados = ['nome_empresa' => $nome];
        DB::table('empresas')->insert($dados);
    }

    public function deletarEmpresa($id)
    {
        DB::table('empresas')->where('id', $id)->delete();
    }

    public function buscarEmpresa($id)
    {
        return DB::table('empresas')->where('id', $id)->get();
    }

    public function editarEmpresa($id, $nome)
    {
        $dados = ['nome_empresa' => $nome];
        DB::table('empresas')->where('id', $id)->update($dados);
    }

    public function filtrarEmpresas($nome)
    {
        $query = DB::table('empresas');

        if ($nome) {
            $query->where('nome_empresa', 'LIKE', '%' . $nome . '%');
        }

        return $query->get();
    }
}