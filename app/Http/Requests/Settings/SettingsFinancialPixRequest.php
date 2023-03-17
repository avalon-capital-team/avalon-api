<?php

namespace App\Http\Requests\Settings;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\FailedValidationTrait;
use App\Rules\CheckVerificationCodeRule;

class SettingsFinancialPixRequest extends FormRequest
{
    use FailedValidationTrait;

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
            'key_type' => ['required', 'string', 'max:255', 'in:CPF,CNPJ,PHONE,EMAIL,RANDOM'],
            'key' => ['required', 'string', 'max:255'],
            'code' => ['required', 'min:6', new CheckVerificationCodeRule('updated_pix')]
        ];
    }

    /**
     * messages
     *
     * @return void
     */
    public function messages()
    {
        return [
            'key.required' => __('Informe a chave.'),
            'key_type.required' => __('Informe o tipo da chave.'),
            'key_type.in' => __('O tipo de chave selecionado é inválido.'),
            'key.required' => __('Informe a chave PIX.'),
            'code.required' => 'Informe o código.',
            'code.digits' => 'O código deve ter no mínimo 6 dígitos'
        ];
    }
}
