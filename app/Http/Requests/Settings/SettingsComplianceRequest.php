<?php

namespace App\Http\Requests\Settings;

use App\Traits\FailedValidationTrait;
use Illuminate\Foundation\Http\FormRequest;

class SettingsComplianceRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'doc_front' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
            'doc_back' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
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
            'doc_front.required' => __('Faça Upload da frente do documento'),
            'doc_back.required' => __('Faça Upload do verso do documento'),
            'doc_front.mimes' => __('O arquivo pode ser: PDF, JPG ou PNG'),
            'doc_front.max' => __('O arquivo deve ter no máximo 2MB'),
            'doc_back.mimes' => __('O arquivo pode ser: PDF, JPG ou PNG'),
            'doc_back.max' => __('O arquivo deve ter no máximo 2MB'),
        ];
    }
}
