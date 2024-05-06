<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmpresaRequest extends FormRequest
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
            "nome_empresa" => "required|string|unique:empresas"
        ];
    }

    public function messages()
    {
        return [
            "nome_empresa.required" => "O campo EMPRESA é obrigatório!",
            "nome_empresa.string" => "O campo EMPRESA é o tipo texto!",
            "nome_empresa.unique" => "Essa empresa já está cadastrada!",
        ];
    }

}
