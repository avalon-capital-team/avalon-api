<?php

namespace App\Http\Requests\User;

use App\Traits\FailedValidationTrait;
use Illuminate\Foundation\Http\FormRequest;

class UserComplianceRequest extends FormRequest
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
            'doc_front'     => ['required', 'file', 'mimes:jpg,jpeg,png'],
            'doc_back'      => ['required', 'file', 'mimes:jpg,jpeg,png'],
            'proof_address'     => ['file', 'mimes:jpg,jpeg,png', 'max:2048'],
            'terms_and_police'      => ['accepted']
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
            'doc_front.required'    => __('A frente do documento é obrigatório'),
            'doc_front.file'    => __('O arquivo é inválido'),
            'doc_back.required'    => __('O verso do documento é obrigatório'),
            'doc_back.file'    => __('O arquivo é inválido'),
            'proof_address.file'    => __('O arquivo é inválido'),
            'terms_and_police.required'    => __('Aceite os termos de política'),
        ];
    }
}
