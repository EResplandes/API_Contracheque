<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Contracheque;
use Illuminate\Http\Request;
use App\Mail\ContrachequeEnviadoMail;
use Illuminate\Support\Facades\Mail;


class ContrachequeController extends Controller
{

    protected $contracheque;

    public function __construct(Contracheque $contracheque){

        $this->contracheque = $contracheque;

    }

    public function index()
    {

        // Query para buscar todos os dados com os funcionários e sua respectiva empresa
        $funcionarios = DB::table('funcionarios')
        ->join('empresas', 'empresas.id', '=', 'funcionarios.fk_empresa')
        ->select('funcionarios.id', 'funcionarios.nome_completo', 'funcionarios.cpf', 'funcionarios.tipo_usuario', 'empresas.nome_empresa')
        ->get();

        return response()->json(['Informações' => $funcionarios]); // Retornando resposta para a requisição
    }

    public function busca($id)
    {

        // Query para buscar os contracheques de uma pessoa em expecifico
        $dados = DB::table('contracheques')
        ->join('funcionarios', 'funcionarios.id', '=', 'contracheques.fk_funcionarios')
        ->select('funcionarios.nome_completo', 'contracheques.mes', 'contracheques.ano', 'contracheques.status', 'contracheques.diretorio')
        ->where('contracheques.fk_funcioario', $id)
        ->get();

        return response()->json(['Contracheques: ' => $dados]); // Retornando resposta para a requisição

    }

    public function cadastro(Request $request)
    {

        // Validandno todos os dados vindo do $request
        $validator = Validator::make($request->all(), $this->contracheque->rules(), $this->contracheque->feedback());

        // Se as informações passarem pelas validações ele registra no banco e retorna a mensagem de registrado com sucesso!
        if ($validator->fails()){

            return response()->json(['Erro: ' => $validator->errors()], 422); // Retornando o erro para a requisição

       } else {

            $ano = $request->input('ano');
            $mes = $request->input('mes');

            $directory = "Contracheques/{$ano}/{$mes}"; // Definindo um diretório para salvar arquivos

            $urn_contracheque = $request->file('diretorio')->store($directory, 'public'); // Salvando o contracheque

            $dados = [
                'mes' => $request->input('mes'),
                'ano' => $request->input('ano'),
                'status' => '0',
                'diretorio' => $urn_contracheque,
                'fk_funcionario' => $request->input('fk_funcionario'),
            ];

            $email = DB::table('funcionarios')->where('id', $request->input('fk_funcionario'))->select('email')->get(); // Pegando o e-mail para enviar notificação

            DB::table('contracheques')->insert($dados); // Inserindo os dados no bando de dados

            
            Mail::to($email)->send(new ContrachequeEnviadoMail($mes, $ano)); // Enviando o e-mail e pessando os parametros de mes e ano

            return response()->json(['Mensagem' => 'Contracheque cadastrado com sucesso!']); // Retornando a resposta para a requisição

        }        
    }

    public function deleta($id)
    {

        if(is_null($id)){

            return response()->json(['Erro:' => 'O id é obrigatório']); // Verificando se o id está vazio

        } elseif(!is_numeric($id)){

            return response()->json(['Erro:' => 'O id deve ser um interio!']); // Verificando se o id é um inteiro

        } else {

           DB::table('contracheques')->where('id', $id)->delete(); // Deletando o contracheque

           return response()->json(['Mensagem: ' => 'Contracheque deletado com sucesso!']); // Retornando resposta para a requisição

        }

    }

}
