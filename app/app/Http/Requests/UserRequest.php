<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'email'=> 'required|unique:users',
            'tipouser' => 'required|digits:1|in:0,1',
            'cpf' =>  'required_if:tipouser,0|numeric|digits:11|unique:users',
            'cnpj' => 'required_if:tipouser,1|numeric|digits:14|unique:users',
        ];
    }

    public function messages()
    {
        return[
            'tipouser.required' => 'O campo :attribute deve ser preenchido com 0 (Fisica) ou 1 (Juridico)',
            'required' => 'O campo :attribute deve ser preenchido',
            'digits' => 'O campo :attribute deve ter exatamente :digits caracteres',
            'numeric' => 'O campo :attribute deve ser númerico',
            'required_if' => 'O campo :attribute deve ser preenchido',
            'unique' => 'Já existe :attribute com o mesmo valor cadastrado. Ele deve ser unico.'
        ];
    }

}
