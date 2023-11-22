<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empresa;
use App\Services\EmpresaService;
use App\Http\Requests\EmpresaRequest;


class EmpresaController extends Controller
{

    protected $empresa;
    protected $empresaService;

    public function __construct(Empresa $empresa, EmpresaService $empresaService)
    {
        $this->empresaService = $empresaService;

        $this->empresa = $empresa;
    }

    public function index()
    {

        $dados = $this->empresaService->getAll();
        return response()->json(['Empresas' => $dados]);

    }

    public function cadastro(EmpresaRequest $request)
    {
     
            $nome_empresa = $request->input('nome_empresa'); // Pega o nome da empresa
            $this->empresaService->cadastrarEmpresa($nome_empresa); 

            return response()->json(['Mensagem' => 'Cadastro realizado com sucesso!']); // Retornando a respota de sucesso para a requisição

    }

    public function deleta($id = null)
    {
 
            $this->empresaService->deletarEmpresa($id);
            return response()->json(['Mensagem: ' => 'Empresa deletada com sucesso!']); // Retornando resposa para a requisição
        
    }

    public function busca($id)
    {

            $dados = $this->empresaService->buscarEmpresa($id); // Busca a empresa de acordo com o id
            return response()->json(['Dados: ' => $dados]); // Retornando os dados para a requisição
        
    }

    public function edita(EmpresaRequest $request, $id)
    {
                // Armazenando as informações do request no array
                $nome_empresa = $request->input('nome_empresa');

                $this->empresaService->editarEmpresa($id, $nome_empresa); // Edita o nome da empresa de acordo com o id

                return response()->json(['Mensagem: ' => 'Dados atualizados com sucesso!']); // Retornando a respota de sucesso para a requisição

    }

    public function filtro(Request $request)
    {

            $nome_empresa = $request->input('nome_empresa');
            
            // Execute a consulta e obtenha os resultados
            $resultados = $this->empresaService->filtrarEmpresas($nome_empresa);

            // Retorne os resultados para a visualização
            return response()->json(['Empresas: ' => $resultados]);

    }
}
