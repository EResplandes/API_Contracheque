<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;



class Funcionario extends Authenticatable implements JWTSubject
{

    use HasApiTokens, HasFactory, Notifiable;


    // Defina a tabela correspondente
    protected $table = 'funcionarios';

    protected $fillable = ['cpf', 'password', 'nome_completo', 'tipo_usuario', 'fk_empresa'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }  

    public function rules(){
        return [
            'nome_completo' => 'required|string|unique:funcionarios',
            'cpf' => 'required|string|unique:funcionarios',
            'password' => 'required|string',
            'tipo_usuario' => 'required|string',
            'fk_empresa' => 'required|integer'
        ];
    }

    public function feedback(){
        return [
            'nome_completo.required' => 'O campo "nome_completo" é obrigatório!',
            'nome_completo.string' => 'O campo "nome_completo" deve ser um texto!',
            'nome_completo.unique' => 'O funcionário já está cadastrado!',
            'cpf.required' => 'O campo "CPF" é obrigatório!',
            'cpf.string' => 'O campo "CPF" deve ser um texto por conta do . e -!',
            'cpf.unique' => 'O cpf ja está cadastrado!',
            'password.required' => 'O campo "password" é obrigatório!',
            'tipo_usuario.required' => 'O campo "tipo_usuario" é obrigatório!',
            'tipo_usuario.string' => 'O campo "tipo usuario" deve ser um texto!',
            'fk_empresa.requird' => 'O campo "fk_empresa" é obrigatório!',
            'fk_empresa.integer' => 'O campo "fk_empresa" deve ser um inteiro!'

        ];
    }

    public function rulesUpdate(){
        return [
            'nome_completo' => 'string|unique:funcionarios',
            'cpf' => 'string|unique:funcionarios',
            'password' => 'string',
            'tipo_usuario' => 'string',
            'fk_empresa' => 'integer'
        ];
    }

    public function feedbackUpdate(){
        return [
            'nome_completo.string' => 'O campo "nome_completo" deve ser um texto!',
            'nome_completo.unique' => 'O funcionário já está cadastrado!',
            'cpf.string' => 'O campo "CPF" deve ser um texto por conta do . e -!',
            'cpf.unique' => 'O cpf ja está cadastrado!',
            'tipo_usuario.string' => 'O campo "tipo usuario" deve ser um texto!',
            'fk_empresa.requird' => 'O campo "fk_empresa" é obrigatório!',
            'fk_empresa.integer' => 'O campo "fk_empresa" deve ser um inteiro!'
        ];
    }


}
