<?php

namespace App\Http\Requests\v2;

use Illuminate\Foundation\Http\FormRequest;
// use Illuminate\Validation\Rule;

class CurrencyCrawlingRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'codes' => 'required_without:numbers|array|min:1|nullable',
            'codes.*' => 'string|min:3|max:3|regex:/[a-zA-Z]$/',
            'numbers' => 'required_without:codes|array|min:1|nullable',
            'numbers.*' => 'string|min:3|max:3|regex:/[0-9]$/',
        ];
    }

    public function messages()
    {
        return [
            'numbers.required_without' => 'É necessário passar o campo numbers',
            'codes.required_without' => 'É necessário passar o campo codes',
            'codes.*.string' => 'É necessário que o código seja do tipo texto',
            'codes.min' => 'É necessário conter pelo menos um código',
            'codes.*.max' => 'É necessário que o cógido tenho 3 caracteres',
            'codes.*.min' => 'É necessário que o cógido tenho 3 caracteres',
            'codes.*.regex' => 'É necessário que o cógido seja composto somente por letras',
            'numbers.*.string' => 'É necessário que a sequência numérica seja do tipo texto',
            'numbers.*.regex' => 'É necessário que a sequência  seja composto somente por números',
            'numbers.min' => 'É necessário conter pelo menos um número',
            'numbers.*.max' => 'É necessário que o cógido tenho 3 caracteres númerico',
            'numbers.*.min' => 'É necessário que o cógido tenho 3 caracteres númerico',
        ];
    }
}
