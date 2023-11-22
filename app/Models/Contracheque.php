<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contracheque extends Model
{
    use HasFactory;

    protected $fillable = ['mes', 'ano', 'status', 'diretorio', 'fk_funcionario'];

}
