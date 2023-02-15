<?php

namespace App\Http\Requests\Auth;

use App\Traits\FailedValidationTrait;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'min:3', 'alpha_dash', 'max:255', 'unique:users,username'],
            'email' => ['required', 'email', 'unique:users,email'],
            'birth_date' => ['required', 'string'],
            'password' => ['required', 'confirmed', 'min:6', 'string'],
            'sponsor_username' => ['nullable']
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
            'name.required'    => __('O nome completo é obrigatório'),
            'username.required'    => __('O nome de usuário é obrigatório'),
            'username.unique'    => __('O nome de usuário já esta sendo utilizado'),
            'username.alpha_dash'    => __('Não pode conter espaços'),
            'birth_date.required' => __('A Data de nascimento é obrigatória'),
            'email.required'    => __('O e-mail é obrigatório'),
            'email.unique'    => __('O e-mail já esta sendo utilizado'),
            'password.required' => __('A senha é obrigatória'),
            'password.confirmed' => __('A confirmação da senha esta incorreta'),
            'password.min' => __('A senha deve conter no mínimo 6 carácteres'),
            'sponsor_username.exists' => __('O indicador é inválido')
        ];
    }
}
