<?php

namespace App\Http\Requests\Settings;

use App\Traits\FailedValidationTrait;
use Illuminate\Foundation\Http\FormRequest;

class SettingsGeneralRequest extends FormRequest
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
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email' . ((!auth()->guest()) ? ',' . auth()->user()->id : '')],
            'phone' => ['required', 'min:10', 'regex:/^([0-9\s\-\+\(\)]*)$/'],
            'birth_date' => ['required', 'date_format:Y-m-d', 'before:-18 years'],
            'country' => ['exists:data_countries,name'],
            'genre' => ['exists:data_genres,name'],
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
            'email.required' => __('O e-mail é obrigatório.'),
            'email.email' => __('E-mail inválido.'),
            'email.unique' => __('E-mail informado já esta em uso.'),
            'phone.required' => __('O celular é obrigatório.'),
            'phone.regex' => __('O celular esta no formato inválido.'),
            'phone.min' => __('O celular deve conter no mínimo 10 números.'),
            'birth_date.required' => __('A idade é obrigatória.'),
            'birth_date.before' => __('A idade mínima é 18 anos.'),
            'country' => __('Selecione o seu país'),
            'country' => __('Selecione um país válido.')
        ];
    }
}
