<?php

namespace App\Http\Requests\Settings;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\FailedValidationTrait;
use App\Rules\CheckVerificationCodeRule;

class SettingsFinancialCoinRequest extends FormRequest
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
            'coin_id' => ['required', 'exists:coins,id'],
            'network' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string',],
            'code' => ['required', 'min:6', new CheckVerificationCodeRule('updated_coin')]
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
            'coin_id.required' => __('Selecione a moeda.'),
            'network.required' => __('Selecione o tipo da rede.'),
            'coin_id.exists' => __('A moeda selecionado é inválido.'),
            'network.required' => __('Informe o tipo da rede.'),
            'address.required' => __('Informe o endereço de sua wallet.'),
        ];
    }
}
