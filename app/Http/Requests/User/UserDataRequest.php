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
    return false;
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, mixed>
   */
  public function rules()
  {
    return [
      'name' => 'sometimes|required|string',
      'email' => 'sometimes|required|email',
      // ... (regras para outros campos do usuário)

      'profile.ong_id' => 'nullable',
      'profile.avatar' => 'nullable',

      'address.cep' => 'nullable',
      'address.street' => 'nullable',
      // ... (regras para outros campos do endereço)

      'security.google_2fa' => 'nullable',

      'compliance.status_id' => 'nullable|integer',
      'compliance.message' => 'nullable|string',
      'compliance.approved_at' => 'nullable|date',

      'financial.*.type' => 'required|string',
      'financial.*.data' => 'nullable',

      'plan.*.plan_id' => 'required|integer',
      'plan.*.coin_id' => 'required|integer',
      'plan.*.payment_method_id' => 'required|integer',
      'plan.*.amount' => 'required|numeric',
      'plan.*.income' => 'required|numeric',
      'plan.*.acting' => 'required|boolean',
      'plan.*.withdrawal_report' => 'required|boolean',
      'plan.*.payment_voucher_url' => 'nullable|url',
      'plan.*.activated_at' => 'nullable|date',
  ];
  }
}
