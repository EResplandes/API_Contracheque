<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contracheque extends Model
{
    use HasFactory;

    protected $fillable = ['mes', 'ano', 'status', 'diretorio', 'fk_funcionario'];

    public function rules()
    {
        return [
            'mes' => 'required|integer',
            'ano' => 'required|integer',
            'status' => 'required|integer',
            'diretorio' => 'required|file|mimes:pdf',
            'fk_funcionario' => 'required'
        ];
    }

    public function feedback()
    {
        return [
            'mes.required' => 'O campo "mes" é obrigatório!',
            'mes.integer' => 'O campo "mes" deve ser um inteiro!',
            'ano.required' => 'O campo "ano" é obrigatório!',
            'ano.integer' => 'O campo "ano" deve ser um inteiro!',
            'status.required' => 'O campo "status" é obrigatório!',
            'status.integer' => 'O campo "status" deve ser um inteiro!',
            'diretorio.required' => 'O contracheque é obrigatório!',
            'diretorio.mimes' => 'O contracheque deve estar em PDF!',
            'fk_funcionario' => 'O campo "fk_funcionario" é obrigatório!'
        ];
    }

}
