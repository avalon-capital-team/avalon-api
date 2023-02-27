<?php

namespace App\Http\Requests\User\Document;

use Illuminate\Foundation\Http\FormRequest;

class ComplianceCompanyRequest extends FormRequest
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
            'REGISTRATION_COMPANY' => ['required', 'file', 'mimes:jpg,jpeg,png', 'max:2048'],
            'COMPANY_LEGAL_ADDRESS' => ['required', 'file', 'mimes:jpg,jpeg,png', 'max:2048'],
            'AUTHORISED_PERSON' => ['required', 'file', 'mimes:jpg,jpeg,png', 'max:2048'],
            'terms_and_police' => ['accepted']
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
            'REGISTRATION_COMPANY.required' => __('Faça Upload do Cartão do CNPJ.'),
            'REGISTRATION_COMPANY.mimes' => __('O arquivo do Cartão do CNPJ deve ter no máximo 2MB.'),
            'COMPANY_LEGAL_ADDRESS.required' => __('Faça Upload do Comprovante de Endereço.'),
            'COMPANY_LEGAL_ADDRESS.mimes' => __('O arquivo do Comprovante de Endereço deve ter no máximo 2MB.'),
            'AUTHORISED_PERSON.required' => __('Faça Upload do Contrato Social.'),
            'AUTHORISED_PERSON.mimes' => __('O arquivo do Contrato Social deve ter no máximo 2MB.'),
            'terms_and_police.accepted' => __('É obrigatório aceitar os termos para continuar.'),
        ];
    }
}
