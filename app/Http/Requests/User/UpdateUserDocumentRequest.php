<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserDocumentRequest extends FormRequest
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
            'doc_front' => ['required', 'file', 'mimes:jpg,jpeg,png', 'max:2048'],
            'doc_back' => ['required', 'file', 'mimes:jpg,jpeg,png', 'max:2048'],
            'proof_address' => ['required', 'file', 'mimes:jpg,jpeg,png', 'max:2048'],
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
            'doc_front.required' => __('Faça Upload da Frente seu Documento de Identificação.'),
            'doc_front.mimes' => __('O arquivo da Frente do seu Documento deve ter no máximo 2MB.'),
            'doc_back.required' => __('Faça Upload da Verso seu Documento de Identificação.'),
            'doc_back.mimes' => __('O arquivo da Verso do seu Documento deve ter no máximo 2MB.'),
            'proof_address.required' => __('Faça Upload do seu Comprovante de Residência.'),
            'proof_address.mimes' => __('O arquivo do Comprovante de Endereço deve ter no máximo 2MB.'),
            'terms_and_police.accepted' => __('É obrigatório aceitar os termos para continuar.'),
        ];
    }
}
