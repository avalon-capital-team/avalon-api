<?php

namespace App\Http\Requests\Settings;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\FailedValidationTrait;
use App\Rules\CheckVerificationCodeRule;

class SettingsFinancialBankRequest extends FormRequest
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
            'bank_id' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'max:255'],
            'account' => ['required', 'numeric', 'digits_between:1,10'],
            'account_digit' => ['required', 'numeric', 'digits_between:1,10'],
            'agency' => ['required', 'string', 'digits_between:1,10'],
            'code' => ['required', 'min:6', new CheckVerificationCodeRule('updated_bank')]
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
            'bank_id.required' => __('Selecione o Banco.'),
            'bank_id.exists' => __('O Banco selecionado é inválido.'),
            'type.required' => __('Informe o tipo de conta.'),
            'type.in' => __('O Tipo de conta selecionado é inválido.'),
            'account.required' => __('Informe o número da sua conta.'),
            'account_digit.required' => __('Informe o dígito da sua conta.'),
            'agency.required' => __('Informe o número da sua agencia.'),
        ];
    }
}
