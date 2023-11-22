<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FuncionarioRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'nome_completo' => 'required|string|unique:funcionarios',
            'email' => 'string|unique:funcionarios|required',
            'cpf' => 'required|string|unique:funcionarios',
            'tipo_usuario' => 'required|string',
            'fk_empresa' => 'required|integer'
        ];
    }

    public function messages()
    {
        return [
            'nome_completo.required' => 'O campo "nome_completo" é obrigatório!',
            'nome_completo.string' => 'O campo "nome_completo" deve ser um texto!',
            'nome_completo.unique' => 'O funcionário já está cadastrado!',
            'email.required' => 'O campo "email" é obrigatório!',
            'email.string' => 'O campo "email" deve ser um texto!',
            'email.unique' => 'O email já está cadastrado!',
            'cpf.required' => 'O campo "CPF" é obrigatório!',
            'cpf.string' => 'O campo "CPF" deve ser um texto por conta do . e -!',
            'cpf.unique' => 'O cpf ja está cadastrado!',
            'tipo_usuario.required' => 'O campo "tipo_usuario" é obrigatório!',
            'tipo_usuario.string' => 'O campo "tipo usuario" deve ser um texto!',
            'fk_empresa.requird' => 'O campo "fk_empresa" é obrigatório!',
            'fk_empresa.integer' => 'O campo "fk_empresa" deve ser um inteiro!'
        ];
    }
}
