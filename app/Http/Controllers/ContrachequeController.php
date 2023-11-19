<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Contracheque;
use Illuminate\Http\Request;
use App\Mail\ContrachequeEnviadoMail;
use Illuminate\Support\Facades\Mail;
use App\Services\ContrachequeService;


class ContrachequeController extends Controller
{

    protected $contracheque;
    protected $contrachequeService;

    public function __construct(Contracheque $contracheque, ContrachequeService $contrachequeService)
    {

        $this->contracheque = $contracheque;

        $this->contrachequeService = $contrachequeService;
    }

    public function index()
    {

        $dados = $this->contrachequeService->getAll(); // Query para buscar todos os dados com os funcionários e sua respectiva empresa

        return response()->json(['Informações' => $dados]); // Retornando resposta para a requisição

    }

    public function busca($id)
    {

        $dados = $this->contrachequeService->busca($id); // Query para buscar os contracheques de acordo com o id

        return response()->json(['Contracheques: ' => $dados]); // Retornando resposta para a requisição

    }

    public function buscaContracheque($id)
    {

        $mesAtual = date('m'); // Obtém o número do mês atual (exemplo: "10" para outubro)

        $dados = $this->contrachequeService->buscaContracheque($id, $mesAtual); // Query para buscar os contracheques de uma pessoa

        if (count($dados) == 0) {
            return response()->json(false); // Retornando resposta para a requisição
        } else {
            return response()->json(true); // Retornando resposta para a requisição
        }
    }

    public function buscaPendencias()
    {

        $mesAtual = date('m');

        $funcionariosSemComprovante = $this->contrachequeService->buscaPendencias($mesAtual); // Query responsável por buscar o funcionários que não tem contracheque lançado no mês atual

        return response()->json(['Dados:' => $funcionariosSemComprovante]); // Retorna resposta para a requisição

    }

    public function totalPendencias()
    {

        $mesAtual = date('m');

        $total = $this->contrachequeService->totalPendencias($mesAtual);

        return response()->json(['Total:' => $total]); // Retorna resposta para a requisição

    }

    public function cadastro(Request $request)
    {

        // Validandno todos os dados vindo do $request
        $validator = Validator::make($request->all(), $this->contracheque->rules(), $this->contracheque->feedback());

        // Se as informações passarem pelas validações ele registra no banco e retorna a mensagem de registrado com sucesso!
        if ($validator->fails()) {

            return response()->json(['Erro: ' => $validator->errors()], 422); // Retornando o erro para a requisição

        } else {

            $ano = $request->input('ano');
            $mes = $request->input('mes');
            $fk_funcionario = $request->input('fk_funcionario');

            $directory = "Contracheques/{$ano}/{$mes}"; // Definindo um diretório para salvar arquivos

            $urn_contracheque = $request->file('diretorio')->store($directory, 'public'); // Salvando o contracheque

            $dados = [
                'mes' => $request->input('mes'),
                'ano' => $request->input('ano'),
                'status' => '0',
                'diretorio' => $urn_contracheque,
                'fk_funcionario' => $request->input('fk_funcionario'),
            ];

            $this->contrachequeService->cadastro($dados, $mes, $ano);

            return response()->json(['Mensagem' => 'Contracheque cadastrado com sucesso!']); // Retornando a resposta para a requisição

        }
    }

    public function deleta($id)
    {

        if (is_null($id)) {

            return response()->json(['Erro:' => 'O id é obrigatório']); // Verificando se o id está vazio

        } elseif (!is_numeric($id)) {

            return response()->json(['Erro:' => 'O id deve ser um interio!']); // Verificando se o id é um inteiro

        } else {

            $this->contrachequeService->deleta($id); // Deleta o contracheque de acordo com o id

            return response()->json(['Mensagem: ' => 'Contracheque deletado com sucesso!']); // Retornando resposta para a requisição

        }
    }

    public function atualizaStatus($id)
    {

        if (is_null($id)) {

            return response()->json(['Erro:' => 'O id é obrigatório']); // Verificando se o id está vazio

        } elseif (!is_numeric($id)) {

            return response()->json(['Erro:' => 'O id deve ser um interio!']); // Verificando se o id é um inteiro

        } else {

            $this->contrachequeService->atualizaStatus($id);

            return response()->json(['Mensagem: ' => 'Contracheque Visualizado com Sucesso!!']); // Retornando resposta para a requisição

        }
    }
}
