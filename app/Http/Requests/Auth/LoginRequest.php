<?php

namespace App\Http\Requests\Auth;

use App\Traits\FailedValidationTrait;
use Illuminate\Foundation\Http\FormRequest;
    use App\Rules\CheckVerificationCodeRule;


class LoginRequest extends FormRequest
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
            'email' => ['required', 'email', 'string'],
            'password' => ['required', 'string'],
            'code' => ['nullable', 'min:6']
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
            'email.required' => __('O e-mail é obrigatório'),
            'email.email' => __('O e-mail informado é inválido'),
            'password.required' => __('A senha é obrigatória'),
            'code.required' => __('O codigo é obrigatória'),
        ];
    }
}
