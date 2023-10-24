<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Funcionario;
use Illuminate\Support\Facades\Hash;




class FuncionarioController extends Controller
{   

    protected $funcionario;

    public function __construct(Funcionario $funcionario)
    {
        $this->funcionario = $funcionario;
    }
    
    public function index(){

        // Query para buscar todos os dados com os funcionários e sua respectiva empresa
        $dados = DB::table('funcionarios')
        ->join('empresas', 'empresas.id', '=', 'funcionarios.fk_empresa')
        ->select('funcionarios.id', 'funcionarios.nome_completo', 'funcionarios.cpf', 'funcionarios.tipo_usuario', 'funcionarios.email','empresas.nome_empresa')
        ->get();

        return response()->json(['Dados:' => $dados]); // Retornando a resposta para a requisição com dados de cada funcionário

    }

    public function cadastro(Request $request)
    {

            // Validandno todos os dados vindo do $request
            $validator = Validator::make($request->all(), $this->funcionario->rules(), $this->funcionario->feedback());

            // Se as informações passarem pelas validações ele registra no banco e retorna a mensagem de registrado com sucesso!
            if ($validator->fails()){
    
                return response()->json(['Erro: ' => $validator->errors()], 422); // Retornando o erro para a requisição
    
           } else {

                $senha = $request->input('password');

                // Armazenando as informações do request no array
                $dados = [
                    'nome_completo' => strtoupper($request->input('nome_completo')),
                    'email' => $request->input('email'),
                    'cpf' => $request->input('cpf'),
                    'password' => Hash::make($senha),
                    'tipo_usuario' => strtoupper($request->input('tipo_usuario')),
                    'fk_empresa' => $request-> input('fk_empresa')
                ];
    
                DB::table('funcionarios')->insert($dados); // Inserindo no banco de dados
    
                return response()->json(['Mensagem: ' => 'Cadastro realizado com sucesso!']); // Retornando a respota de sucesso para a requisição
    
            }

    }

    public function deleta($id) 
    {

        if(is_null($id)) {

            return response()->json(['Erro: ' => 'O id é obrigatório!']); // Verificando se o id está vazio

        } elseif(!is_numeric($id)){

            return response()->json(['Erro>' => 'O id deve ser um inteiro']); // Verificando se o id é um inteiro

        } else {

            DB::table('funcionarios')->where('id', $id)->delete(); // Deletando o funcionário de acordo com o id

            return response()->json(['Mesagem:' => 'O funcionário foi deletado com sucesso!']); // Retornando resposa para a requisição

        }

    }

    public function desativa($id)
    {

        if(is_null($id)) {

            return response()->json(['Erro: ' => 'O id é obrigatório!']); // Verificando se o id está vazio

        } elseif(!is_numeric($id)){

            return response()->json(['Erro>' => 'O id deve ser um inteiro']); // Verificando se o id é um inteiro

        } else {

            $dados = [
                'tipo_usuario' => 'DESATIVADO',
            ];

            DB::table('funcionarios')->where('id', $id)->update($dados); // Atualizando o status do funcionário para desativado

            return response()->json(['Mesagem:' => 'O funcionário foi desativado com sucesso!']); // Retornando resposa para a requisição

        }

    }

    public function busca($id)
    {

        if(is_null($id)) {

            return response()->json(['Erro: ' => 'O id é obrigatório!']); // Verificando se o id está vazio

        } elseif(!is_numeric($id)){

            return response()->json(['Erro>' => 'O id deve ser um inteiro']); // Verificando se o id é um inteiro

        } else {

            $dados = DB::table('funcionarios')->where('id', $id)->get(); // Pegando informações do funcionário

            return response()->json(['Dados:' => $dados]); // Retornando resposa para a requisição

        }

    }

    public function edita(Request $request, $id)
    {

        if(is_null($id)) {

            return response()->json(['Erro: ' => 'O id é obrigatório!']); // Verificando se o id está vazio

        } elseif(!is_numeric($id)){

            return response()->json(['Erro>' => 'O id deve ser um inteiro']); // Verificando se o id é um inteiro

        } else {

            // Validandno todos os dados vindo do $request
            $validator = Validator::make($request->all(), $this->funcionario->rulesUpdate(), $this->funcionario->feedbackUpdate());

            // Se as informações passarem pelas validações ele registra no banco e retorna a mensagem de registrado com sucesso!
            if ($validator->fails()){
    
                return response()->json(['Erro: ' => $validator->errors()], 422); // Retornando o erro para a requisição
    
           } else {

                $nome_completo = DB::table('funcionarios')->where('id', $id)->select('nome_completo')->get();
                $nome_completo_campo = $nome_completo[0]->nome_completo;
                $cpf = DB::table('funcionarios')->where('id', $id)->select('cpf')->get();
                $cpf_campo = $cpf[0]->cpf;
                $tipo_usuario = DB::table('funcionarios')-> where('id', $id)->select('tipo_usuario')->get();    
                $tipo_usuario_campo = $tipo_usuario[0]->tipo_usuario;            
                $fk_empresa = DB::table('funcionarios')->where('id', $id)->select('fk_empresa')->get();
                $fk_empresa_campo = $fk_empresa[0]->fk_empresa;
                
                // Armazenando as informações do request no array
                $dados = [
                ];

                // Verifica se o request está vazio com o metodo filled(retora um true se tiver preenchido)
                // Se não estiver preenchido ele busca no banco de dados armazana no array dados
                if ($request->filled('cpf')) {
                    $dados['cpf'] = $request->input('cpf');
                } else {
                    $dados['cpf'] = $cpf_campo;
                }
                
                if ($request->filled('nome_completo')) {
                    $dados['nome_completo'] = $request->input('nome_completo');
                } else {
                    $dados['nome_completo'] = $nome_completo_campo;
                }
                
                if ($request->filled('tipo_usuario')) {
                    $dados['tipo_usuario'] = $request->input('tipo_usuario');
                } else {
                    $dados['tipo_usuario'] = $tipo_usuario_campo;
                }
                
                if ($request->filled('fk_empresa')) {
                    $dados['fk_empresa'] = $request->input('fk_empresa');
                } else {
                    $dados['fk_empresa'] = $fk_empresa_campo;
                }
    
                DB::table('funcionarios')->where('id', $id)->update($dados); // Atualizando dados do funcionário no banco de dados de acordo com o di
    
                return response()->json(['Mensagem: ' => 'Dados atualizado com sucesso!']); // Retornando a respota de sucesso para a requisição
                    
            }


        }

    }

}
