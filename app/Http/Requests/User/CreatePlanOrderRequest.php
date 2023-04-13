<?php

namespace App\Http\Requests\User;

use App\Traits\FailedValidationTrait;
use Illuminate\Foundation\Http\FormRequest;

class CreatePlanOrderRequest extends FormRequest
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
            'plan_id' => ['required', 'exists:data_plans,id'],
            'coin_id' => ['required', 'exists:coins,id'],
            'payment_method' => ['exists:payment_methods,id'],
            'amount' => ['required', 'string'],
            'withdrawal_report' => ['required']
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
            'plan_id.required' => __('Selecione o plano que deseja contratar.'),
            'coin_id.required' => __('Selecione o tipo da moeda que deseja pagar.'),
            'payment_method.required' => __('Selecione o método que deseja depositar.'),
            'amount.required' => __('Nessesário preencher o valor da orden'),
            'withdrawal_report.required' => __('Nessesário setar saque mensal')
        ];
    }
}
