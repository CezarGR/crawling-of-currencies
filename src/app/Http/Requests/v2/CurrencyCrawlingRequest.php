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
            'codes' => 'required_without:numbers|array|min:1',
            'codes.*' => 'string|min:3|max:3',
            'numbers' => 'required_without:codes|array|min:1',
            'numbers.*' => 'string|min:3|max:3',
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
            'numbers.*.string' => 'É necessário que a sequência numérica seja do tipo texto',
            'numbers.min' => 'É necessário conter pelo menos um número',
            'numbers.*.max' => 'É necessário que o cógido tenho 3 caracteres númerico',
        ];
    }
}
