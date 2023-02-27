<?php

namespace App\Http\Requests\User\Document;

use Illuminate\Foundation\Http\FormRequest;

class CompliancePersonRequest extends FormRequest
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
            'FILE_TYPE' => ['required', 'string', 'in:PASSPORT,DRIVERS_LICENSE,GOVERNMENT_ID'],
            'FILE' => ['required', 'file', 'mimes:jpg,jpeg,png', 'max:2048'],
            'FILE_BACK' => ['nullable', 'required_if:FILE_TYPE,DRIVERS_LICENSE,GOVERNMENT_ID', 'file', 'mimes:jpg,jpeg,png', 'max:2048'],
            'SELFIE_IMAGE' => ['required', 'file', 'mimes:jpg,jpeg,png', 'max:2048'],
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
            'FILE_TYPE.required' => __('Selecione o tipo do documento.'),
            'FILE_TYPE.in' => __('Tipo de documento inválido.'),
            'FILE_BACK.required_if' => __('Faça Upload do Verso Documento.'),
            'FILE_BACK.mimes' => __('O verso do Documento deve ter no máximo 2MB.'),
            'FILE.required' => __('Faça Upload do Documento.'),
            'FILE.mimes' => __('O arquivo do Documento deve ter no máximo 2MB.'),
            'SELFIE_IMAGE.required' => __('Faça Upload da sua Selfie.'),
            'SELFIE_IMAGE.mimes' => __('O arquivo da sua Selfie deve ter no máximo 2MB.'),
            'terms_and_police.accepted' => __('É obrigatório aceitar os termos para continuar.'),
        ];
    }
}
