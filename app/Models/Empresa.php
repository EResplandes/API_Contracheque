<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    use HasFactory;

    protected $fillable = ["nome_empresa"];

    public function rules()
    {
        return [
            "nome_empresa" => "required|string|unique:empresas"
        ];
    }

    public function feedback()
    {
        return [
            "nome_empresa.required" => "O campo 'nome_empresa' é obrigatório!",
            "nome_empresa.string" => "O campo 'nome_empresa' é o tipo texto!",
            "nome_empresa.unique" => "Essa empresa já está cadastrada!",
        ];
    }

}
