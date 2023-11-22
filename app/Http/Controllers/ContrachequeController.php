<?php

namespace App\Http\Controllers;

use App\Models\Contracheque;
use App\Services\ContrachequeService;
use App\Http\Requests\ContrachequeRequest;


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

        $this->contrachequeService->buscaContracheque($id); // Query para buscar os contracheques de uma pessoa

    }

    public function buscaPendencias()
    {

        $funcionariosSemComprovante = $this->contrachequeService->buscaPendencias(); // Query responsável por buscar o funcionários que não tem contracheque lançado no mês atual
        return response()->json(['Dados:' => $funcionariosSemComprovante]); // Retorna resposta para a requisição

    }

    public function totalPendencias()
    {

        $total = $this->contrachequeService->totalPendencias();
        return response()->json(['Total:' => $total]); // Retorna resposta para a requisição

    }

    public function cadastro(ContrachequeRequest $request)
    {

        $this->contrachequeService->cadastro($request);
        return response()->json(['Mensagem' => 'Contracheque cadastrado com sucesso!']); // Retornando a resposta para a requisição
    
    }

    public function deleta($id)
    {

        $this->contrachequeService->deleta($id); // Deleta o contracheque de acordo com o id
        return response()->json(['Mensagem: ' => 'Contracheque deletado com sucesso!']); // Retornando resposta para a requisição

    }

    public function atualizaStatus($id)
    {

        $this->contrachequeService->atualizaStatus($id);
        return response()->json(['Mensagem: ' => 'Contracheque Visualizado com Sucesso!!']); // Retornando resposta para a requisição

    }
}
