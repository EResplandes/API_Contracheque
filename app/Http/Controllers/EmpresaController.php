<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Empresa;
use Illuminate\Support\Facades\Validator;
use App\Services\EmpresaService;


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

    public function cadastro(Request $request)
    {

        // Validandno todos os dados vindo do $request
        $validator = Validator::make($request->all(), $this->empresa->rules(), $this->empresa->feedback());

        // Se as informações passarem pelas validações ele registra no banco e retorna a mensagem de registrado com sucesso!
        if ($validator->fails()) {

            return response()->json(['Erro' => $validator->errors()], 422); // Retornando o erro para a requisição

        } else {
            
            $nome_empresa = $request->input('nome_empresa'); // Pega o nome da empresa
            $this->empresaService->cadastrarEmpresa($nome_empresa); 

            return response()->json(['Mensagem' => 'Cadastro realizado com sucesso!']); // Retornando a respota de sucesso para a requisição

        }
    }

    public function deleta($id = null)
    {
        if (is_null($id)) {

            return response()->json(['Erro: ' => 'O id é obrigatório']); // Verficando se o id está vazio

        } elseif (!is_numeric($id)) {

            return response()->json(['Erro>=: ' => 'O id deve ser um interio!']); // Verficando se o id é um inteiro

        } else {

            $this->empresaService->deletarEmpresa($id);

            return response()->json(['Mensagem: ' => 'Empresa deletada com sucesso!']); // Retornando resposa para a requisição

        }
    }

    public function busca($id)
    {

        if (is_null($id)) {

            return response()->json(['Erro:' => 'O id é obrigatório']); // Verificando se o id está vazio

        } elseif (!is_numeric($id)) {

            return response()->json(['Erro:' => 'O id deve ser um interio!']); // Verificando se o id é um inteiro

        } else {

            $dados = $this->empresaService->buscarEmpresa($id); // Busca a empresa de acordo com o id
            return response()->json(['Dados: ' => $dados]); // Retornando os dados para a requisição

        }
    }

    public function edita(Request $request, $id)
    {

        if (is_null($id)) {

            return response()->json(['Erro:' => 'O id é obrigatório!']); // Vericando se o id está vazio

        } elseif (!is_numeric($id)) {

            return response()->json(['Erro>' => 'O id deve ser um inteiro']); // Verificando se o id é um inteiro

        } else {

            // Validandno todos os dados vindo do $request
            $validator = Validator::make($request->all(), $this->empresa->rules(), $this->empresa->feedback());

            // Se as informações passarem pelas validações ele registra no banco e retorna a mensagem de registrado com sucesso!
            if ($validator->fails()) {

                return response()->json(['Erro: ' => $validator->errors()], 422); // Retornando o erro para a requisição

            } else {

                // Armazenando as informações do request no array
                $nome_empresa = $request->input('nome_empresa');

                $this->empresaService->editarEmpresa($id, $nome_empresa); // Edita o nome da empresa de acordo com o id

                return response()->json(['Mensagem: ' => 'Dados atualizados com sucesso!']); // Retornando a respota de sucesso para a requisição

            }
        }
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
