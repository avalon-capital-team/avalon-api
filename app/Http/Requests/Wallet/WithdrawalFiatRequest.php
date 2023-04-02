<?php

namespace App\Http\Requests\Wallet;

use App\Rules\CheckLimitWithdrawalRule;
use App\Rules\CheckVerificationCodeRule;
use Illuminate\Foundation\Http\FormRequest;

class WithdrawalFiatRequest extends FormRequest
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
            'amount' => ['required', 'numeric', 'min:25', new CheckLimitWithdrawalRule()],
            'coin_id' => ['required'],
            'type' => ['required', 'string', 'in:bank,pix'],
            'code' => ['required', 'min:6', new CheckVerificationCodeRule('withdrawal_fiat')]
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
            'coin_id.required' => 'Você precisa escolher uma moeda.',
            'type.required' => 'Você precisa definir um conta de destino.',
            'amount.required' => 'Informe o valor que deseja depositar.',
            'amount.numeric' => 'O valor deve ser um número.',
            'amount.min' => 'O valor mínimo para deposito é de R$25.00',
            'accepted_terms.accepted' => 'Você precisa estar ciente sobre a origem do deposito.',
            'code.required' => 'Informe o código.',
            'code.digits' => 'O código deve ter no mínimo 6 dígitos.'
        ];
    }
}
