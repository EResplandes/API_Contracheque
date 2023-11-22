<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Mail\ContrachequeEnviadoMail;
use Illuminate\Support\Facades\Mail;

class ContrachequeService
{

    public function getAll()
    {

        // Query para buscar todos os dados com os funcionários e sua respectiva empresa
        return DB::table('contracheques')
            ->join('funcionarios', 'funcionarios.id', '=', 'contracheques.fk_funcionario')
            ->join('empresas', 'empresas.id', '=', 'funcionarios.fk_empresa')
            ->select('funcionarios.nome_completo', 'contracheques.mes', 'contracheques.ano', 'contracheques.status', 'contracheques.diretorio', 'empresas.nome_empresa')
            ->get();

    }

    public function busca($id)
    {

        // Query para buscar os contracheques de uma pessoa de acordo com o id
        return DB::table('contracheques')
            ->join('funcionarios', 'funcionarios.id', '=', 'contracheques.fk_funcionario')
            ->join('empresas', 'empresas.id', '=', 'funcionarios.fk_empresa')
            ->select('funcionarios.nome_completo', 'funcionarios.cpf', 'contracheques.id', 'contracheques.mes', 'contracheques.ano', 'contracheques.status', 'contracheques.diretorio', 'empresas.nome_empresa')
            ->where('contracheques.fk_funcionario', $id)
            ->get();

    }

    public function buscaContracheque($id)
    {

        $mesAtual = date('m'); // Obtém o número do mês atual (exemplo: "10" para outubro)

        // Query para buscar os contracheques de uma pessoa
        return DB::table('contracheques')
            ->join('funcionarios', 'funcionarios.id', '=', 'contracheques.fk_funcionario')
            ->join('empresas', 'empresas.id', '=', 'funcionarios.fk_empresa')
            ->select('funcionarios.nome_completo', 'funcionarios.cpf', 'contracheques.id', 'contracheques.mes', 'contracheques.ano', 'contracheques.status', 'contracheques.diretorio', 'empresas.nome_empresa')
            ->where('contracheques.fk_funcionario', $id)
            ->where('mes', $mesAtual)
            ->get();

    }

    public function buscaPendencias()
    {
        $mesAtual = date('m');

        // Query responsável por buscar o funcionários que não tem contracheque lançado no mês atual
        return DB::table('funcionarios')
            ->where('tipo_usuario', 'ATIVO')
            ->whereNotIn('id', function ($query) use ($mesAtual) {
                $query->select('fk_funcionario')
                    ->from('contracheques')
                    ->where('mes', $mesAtual);
            })
            ->select('id', 'nome_completo')
            ->get();
            
    }

    public function totalPendencias()
    {

        $mesAtual = date('m'); // Pega mês atual

        // Query responsável por buscar a quantidade de funcionários que não tem contracheque lançado no mês atual
        return DB::table('funcionarios')
            ->where('tipo_usuario', 'ATIVO')
            ->whereNotIn('id', function ($query) use ($mesAtual) {
                $query->select('fk_funcionario')
                    ->from('contracheques')
                    ->where('mes', $mesAtual);
            })
            ->count();

    }

    public function cadastro($dados, $mes, $ano){

        $email = DB::table('funcionarios')->where('id', $dados['fk_funcionario'])->select('email')->get(); // Pegando o e-mail para enviar notificação
        
        DB::table('contracheques')->insert($dados); // Inserindo os dados no banco de dados

        Mail::to($email)->send(new ContrachequeEnviadoMail($mes, $ano)); // Enviando o e-mail e pessando os parametros de mes e ano

    }

    public function deleta($id){

        DB::table('contracheques')->where('id', $id)->delete(); // Deletando o contracheque

    }

    public function atualizaStatus($id){

        DB::table('contracheques')->where('id', $id)->update(['status' => 1]); // Coloca o status como visualizado

    }

}
