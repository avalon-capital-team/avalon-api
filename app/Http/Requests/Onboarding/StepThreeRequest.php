<?php

namespace App\Http\Requests\Onboarding;

use App\Traits\FailedValidationTrait;
use Illuminate\Foundation\Http\FormRequest;

class StepThreeRequest extends FormRequest
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
            'document_type' => ['required', 'string', 'in:CPF,CNPJ'],
            'document' => ['required', 'string', 'max:255', 'unique:users,document'],
            'name' => ['required', 'string'],
            'phone' => ['required', 'string'],
            'birth_date' => ['required', 'string'],
            'genre_id' => ['required'],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'document_type.required'    => __('O tipo do documento é obrigatório'),
            'document.unique'    => __('O número do documento já esta sendo utilizado'),
            'document.required'    => __('O número do documento é obrigatório'),
            'name.required'    => __('O nome é obrigatório'),
            'phone.required'    => __('O telefone é obrigatório'),
            'birth_date.required'    => __('A data de nacimento é obrigatório'),
            'genre_id.required'    => __('O genero é obrigatório'),
        ];
    }
}
