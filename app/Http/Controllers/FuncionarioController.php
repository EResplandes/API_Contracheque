<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Funcionario;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Services\FuncionarioService;
use App\Http\Requests\FuncionarioRequest;


class FuncionarioController extends Controller
{

    protected $funcionario;
    protected $funcionarioService;

    public function __construct(Funcionario $funcionario, FuncionarioService $funcionarioService)
    {
        $this->funcionario = $funcionario;
        $this->funcionarioService = $funcionarioService;
    }

    public function index()
    {

        $dados = $this->funcionarioService->getAll(); // Query para buscar todas os dados com os funcionários e sua respectiva empresa

        return response()->json(['Dados:' => $dados]); // Retornando a resposta para a requisição com dados de cada funcionário

    }

    public function cadastro(FuncionarioRequest $request)
    {

        $tamanhoSenha = 10; // Defina o comprimento da cadeia desejado
        $email = $request->input('email');
        $cpf = $request->input('cpf');
        $senha = Str::random($tamanhoSenha);

        // Armazenando as informações do request no array
        $dados = [
            'nome_completo' => strtoupper($request->input('nome_completo')),
            'email' => $request->input('email'),
            'cpf' => $request->input('cpf'),
            'password' => Hash::make($senha),
            'tipo_usuario' => 'FUNCIONARIO',
            'fk_empresa' => $request->input('fk_empresa')
        ];

        $this->funcionarioService->cadastro($email, $cpf, $senha, $dados); // Envia o e-mail com usuário e senha e cadastra o usuário

        return response()->json(['Mensagem: ' => 'Cadastro realizado com sucesso!']); // Retornando a respota de sucesso para a requisição
    }

    public function deleta($id)
    {

        $this->funcionarioService->deleta($id); // Deleta funcionário de acordo com o id
        return response()->json(['Mesagem:' => 'O funcionário foi deletado com sucesso!']); // Retornando resposa para a requisição

    }

    public function desativa($id)
    {

        $this->funcionarioService->desativa($id); // Desativa funcionário de acordo com o id
        return response()->json(['Mesagem:' => 'O funcionário foi desativado com sucesso!']); // Retornando resposa para a requisição

    }

    public function busca($id)
    {

        $dados = $this->funcionarioService->busca($id);
        return response()->json(['Dados:' => $dados]); // Retornando resposa para a requisição

    }

    public function edita(Request $request, $id)
    {

        // Validandno todos os dados vindo do $request
        $validator = Validator::make($request->all(), $this->funcionario->rulesUpdate(), $this->funcionario->feedbackUpdate());

        // Se as informações passarem pelas validações ele registra no banco e retorna a mensagem de registrado com sucesso!
        if ($validator->fails()) {

            return response()->json(['Erro: ' => $validator->errors()], 422); // Retornando o erro para a requisição

        } else {

            $nome_completo = DB::table('funcionarios')->where('id', $id)->select('nome_completo')->get();
            $nome_completo_campo = $nome_completo[0]->nome_completo;
            $cpf = DB::table('funcionarios')->where('id', $id)->select('cpf')->get();
            $cpf_campo = $cpf[0]->cpf;
            $tipo_usuario = DB::table('funcionarios')->where('id', $id)->select('tipo_usuario')->get();
            $tipo_usuario_campo = $tipo_usuario[0]->tipo_usuario;
            $fk_empresa = DB::table('funcionarios')->where('id', $id)->select('fk_empresa')->get();
            $fk_empresa_campo = $fk_empresa[0]->fk_empresa;

            // Armazenando as informações do request no array
            $dados = [];

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

            $this->funcionarioService->edita($id, $dados); // Edita o funcionário

            return response()->json(['Mensagem: ' => 'Dados atualizado com sucesso!']); // Retornando a respota de sucesso para a requisição

        }
    }

    public function filtro(Request $request)
    {
        $nome_completo = $request->input('nome_completo');
        $cpf = $request->input('cpf');
        $email = $request->input('email');
        $fk_empresa = $request->input('fk_empresa');

        $resultados = $this->funcionarioService->filtro($nome_completo, $cpf, $email, $fk_empresa); // Filtra o funcionário

        // Retorne os resultados para a visualização
        return response()->json(['Funcionarios:' => $resultados]);
    }

    public function buscaAtivo()
    {

        $dados = $this->funcionarioService->buscaAtivo(); // Busca funcionário de acordo com o id

        return response()->json(['Funcionarios:' => $dados]); // Retorna a resposta para a requisiçãos 

    }

    public function alteraSenha(Request $request, $id)
    {

        // Validandno todos os dados vindo do $request
        $validator = Validator::make($request->all(), $this->funcionario->rulesAltera(), $this->funcionario->feedbackAltera());

        // Se as informações passarem pelas validações ele registra no banco e retorna a mensagem de registrado com sucesso!
        if ($validator->fails()) {

            return response()->json(['Erro: ' => $validator->errors()], 422); // Retornando o erro para a requisição

        } else {

            $dados = [
                'senha_nova' => $request->input('senha_nova'),
                'senha_confere' => $request->input('senha_confere')
            ];

            if ($dados['senha_nova'] != $dados['senha_confere']) {

                return response()->json(['Erro:' => 'A senhas não coincidem!']);
            } else {

                $this->funcionarioService->alteraSenha($id, $dados); // Altera senha

                return response()->json(['Mensagem:' => 'Senha alterada com sucesso!']);
            }
        }
    }
}
