<?php

namespace App\Http\Requests\User;

use App\Traits\FailedValidationTrait;
use Illuminate\Foundation\Http\FormRequest;

class UserComplianceRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return [
            'doc_front'     => ['required', 'file', 'mimes:jpg,jpeg,png', 'max:2048'],
            'doc_back'      => ['required', 'file', 'mimes:jpg,jpeg,png', 'max:2048'],
            'proof_address'     => ['required', 'file', 'mimes:jpg,jpeg,png', 'max:2048'],
            'terms_and_police'      => ['accepted']
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'doc_front.required'    => __('O arquivo é obrigatório'),
            'doc_front.file'    => __('O arquivo é inválido'),
            'doc_back.required'    => __('O arquivo é obrigatório'),
            'doc_back.file'    => __('O arquivo é inválido'),
            'proof_address.required'    => __('O arquivo é obrigatório'),
            'proof_address.file'    => __('O arquivo é inválido'),
            'terms_and_police.required'    => __('Aceite os termos de política'),
        ];
    }
}
