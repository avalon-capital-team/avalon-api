<?php

namespace App\Http\Requests\Settings;

use App\Traits\FailedValidationTrait;
use Illuminate\Foundation\Http\FormRequest;
use App\Rules\CheckVerificationCodeRule;

class SettingsSecurityPasswordRequest extends FormRequest
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
            'password_current' => ['required', 'string'],
            'password_new' => ['required', 'confirmed', 'min:6', 'string'],
            'code' => ['required', 'min:6', new CheckVerificationCodeRule('updated_password')]
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
            'password_current.required' => __('A senha atual é obrigatória'),
            'password_new.required' => __('A senha nova é obrigatória'),
            'password_new.confirmed' => __('A confirmação da senha nova esta incorreta'),
            'password_new.min' => __('A senha nova deve conter no mínimo 6 carácteres'),
        ];
    }
}
