<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UserDataRequest extends FormRequest
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
   * @return array<string, mixed>
   */
  public function rules()
    {
        return [
            'id'     => ['required'],
            'username'      => ['sometimes', 'unique:users,username'],
            'email'      => ['sometimes', 'unique:users,email'],
            'name'      => ['sometimes'],
            'phone'      => ['sometimes'],
            'genre_id'      => ['sometimes'],
            'address.street'     => ['sometimes'],
            'address.neighborhood'     => ['sometimes'],
            'address.city'     => ['sometimes'],
            'address.state'     => ['sometimes'],
            'address.number'     => ['sometimes'],
            'address.cep'     => ['sometimes'],
            'financial.type'     => ['sometimes'],
            'financial.data.key_type'     => ['sometimes'],
            'financial.data.key'     => ['sometimes'],
            'financial.data.bank_id'     => ['sometimes'],
            'financial.data.type'     => ['sometimes'],
            'financial.data.account'     => ['sometimes'],
            'financial.data.account_digit'     => ['sometimes'],
            'financial.data.agency'     => ['sometimes'],
            'financial.data.coin_id'     => ['sometimes'],
            'financial.data.address'     => ['sometimes'],
            'financial.data.network'     => ['sometimes'],
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
            'id.required'    => __('O id do usuario é obrigatório'),
            'username.unique'    => __('Já esta sendo utilizado esse username'),
            'email.unique'    => __('Já esta sendo utilizado esse email'),
        ];
    }
}

