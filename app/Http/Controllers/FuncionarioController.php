<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;



class FuncionarioController extends Controller
{
    
    public function index(){

        DB::table('funcionarios')->get();

    }

}
