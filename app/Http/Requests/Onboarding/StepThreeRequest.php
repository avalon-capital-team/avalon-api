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
            'cep' => ['required', 'string'],
            'street' => ['required', 'string'],
            'neighborhood' => ['required', 'string'],
            'city' => ['required', 'string'],
            'state' => ['required', 'string'],
            'number' => ['required', 'string']
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
            'cep.required'    => __('O cep é obrigatório'),
            'street.required'    => __('A rua é obrigatório'),
            'neighborhood.required'    => __('O bairro é obrigatório'),
            'city.required'    => __('A cidade é obrigatório'),
            'state.required'    => __('O estado é obrigatório'),
            'number.required'    => __('O numero é obrigatório'),
        ];
    }
}
