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
            'numbers.required_without' => 'É necessario passar o campo numbers',
            'codes.required_without' => 'É necessario passar o campo codes',
            'codes.*.string' => 'Código inválido',
            'codes.*.min' => 'Código inválido',
            'codes.*.max' => 'Código inválido',
            'numbers.*.string' => 'Número inválido',
            'numbers.*.min' => 'Número inválido',
            'numbers.*.max' => 'Número inválido',
        ];
    }
}
