<?php

namespace App\Http\Requests\v1;

use Illuminate\Foundation\Http\FormRequest;
// use Illuminate\Validation\Rule;

class CurrencyCrawlingRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'codes' => data_get(
                $this, 
                'code_list', 
                $this->get('code') || $this->get('number')|| $this->get('number_list') ? 
                    (array) $this->get('code') : 
                    null
                ),
            'numbers' => data_get(
                $this, 
                'number_list',
                $this->get('number') || $this->get('code') || $this->get('code_list') ? 
                    (array) $this->get('number') : 
                    null
                ),
        ]);
    }


    public function rules()
    {
        return [
            'codes' => 'required_without:numbers|array',
            'codes.*' => 'string',
            'numbers' => 'required_without:codes|array',
            'numbers.*' => 'integer',
        ];
    }

    public function messages()
    {
        return [
            'numbers.required_without' => 'Necessário o envio do campo number ou number_list',
            'codes.required_without' => 'Necessário o envio do campo code ou code_list',
            'codes.*' => 'O código é nessário ser do tipo texto',
            'numbers.*' => 'O número é nessário ser do tipo inteiro'
        ];
    }
}
