<?php

namespace App\Http\Requests\Settings;

use App\Traits\FailedValidationTrait;
use Illuminate\Foundation\Http\FormRequest;

class SettingsProfileRequest extends FormRequest
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
            'cover' => ['nullable', 'image', 'mimes:jpg,png,jpeg', 'max:2048'],
            'avatar' => ['nullable', 'image', 'mimes:jpg,png,jpeg', 'max:2048'],
            'username' => ['nullable', 'alpha_dash', 'min:3', 'string', 'max:255', 'unique:users,username' . ((!auth()->guest()) ? ',' . auth()->user()->id : '')],
            'name' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'state' => ['nullable', 'string', 'max:255'],
            'bio' => ['nullable', 'string', 'max:255'],
            'profession' => ['nullable', 'string', 'max:255']
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

            'avatar.image' => __('O avatar deve ser uma imagem válida'),
            'avatar.image' => __('Para o avatar o arquivo deve ser JPG ou PNG'),
            'username.required' => __('O nome de usuário é obrigatório'),
            'username.unique' => __('O nome de usuário já esta sendo utilizado'),
            'username.alpha_dash' => __('Não pode conter espaços'),
            'name.required' => __('O nome completo é obrigatório'),
            'bio.max' => __('A bio pode conter até 255 carácteres')
        ];
    }
}
