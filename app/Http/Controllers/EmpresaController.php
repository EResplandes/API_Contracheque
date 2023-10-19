<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Empresa;
use Illuminate\Support\Facades\Validator;


class EmpresaController extends Controller
{   

    protected $empresa;

    public function __construct(Empresa $empresa)
    {
        $this->empresa = $empresa;
    }
        
    public function index()
    {

       $dados =  DB::table('empresas')->orderBy('id', 'asc')->get(); // Pegando os dados de todas empresas

       return response()->json(['Empresas: ' => $dados]); // Retorando a resposta para a requisição

    }

    public function cadastro(Request $request)
    {

        // Validandno todos os dados vindo do $request
        $validator = Validator::make($request->all(), $this->empresa->rules(), $this->empresa->feedback());

        // Se as informações passarem pelas validações ele registra no banco e retorna a mensagem de registrado com sucesso!
        if ($validator->fails()){

            return response()->json(['Erro' => $validator->errors()], 422); // Retornando o erro para a requisição

        } else {

            // Armazenando as informações do request no array
            $dados = [
                'nome_empresa' => $request->input('nome_empresa'),
            ];

            DB::table('empresas')->insert($dados); // Inserindo no banco de dados

            return response()->json(['Mensagem' => 'Cadastro realizado com sucesso!']); // Retornando a respota de sucesso para a requisição

        }

    }

    public function deleta($id = null)
    {   

        if(is_null($id)){

            return response()->json(['Erro: ' => 'O id é obrigatório']); // Verficando se o id está vazio

        } elseif(!is_numeric($id)){

            return response()->json(['Erro>=: ' => 'O id deve ser um interio!']); // Verficando se o id é um inteiro
            
        } else {

            DB::table('empresas')->where('id', $id)->delete(); // Deletando a empresa de acordo com id

            return response()->json(['Mensagem: ' => 'Empresa deletada com sucesso!']); // Retornando resposa para a requisição

        }

    }

    public function busca($id)
    {

        if(is_null($id)){

            return response()->json(['Erro:' => 'O id é obrigatório']); // Verificando se o id está vazio

        } elseif(!is_numeric($id)){

            return response()->json(['Erro:' => 'O id deve ser um interio!']); // Verificando se o id é um inteiro

        } else {

            $dados = DB::table('empresas')->where('id', $id)->get(); // Busca os dados da empresa de acordo com id

            return response()->json(['Dados: ' => $dados]); // Retornando os dados para a requisição

        }

    }

    public function edita(Request $request, $id)
    {

        if(is_null($id)){

            return response()->json(['Erro:' => 'O id é obrigatório!']); // Vericando se o id está vazio

        } elseif (!is_numeric($id)){

            return response()->json(['Erro>' => 'O id deve ser um inteiro']); // Verificando se o id é um inteiro

        } else {

            // Validandno todos os dados vindo do $request
            $validator = Validator::make($request->all(), $this->empresa->rules(), $this->empresa->feedback());

            // Se as informações passarem pelas validações ele registra no banco e retorna a mensagem de registrado com sucesso!
            if ($validator->fails()){

                return response()->json(['Erro: ' => $validator->errors()], 422); // Retornando o erro para a requisição

            } else {

                // Armazenando as informações do request no array
                $dados = [
                    'nome_empresa' => $request->input('nome_empresa'),
                ];

                DB::table('empresas')->where('id', $id)->update($dados); // Atualizando informações no banco de dados

                return response()->json(['Mensagem: ' => 'Dados atualizados com sucesso!']); // Retornando a respota de sucesso para a requisição

        }

        }

        

    }

}

