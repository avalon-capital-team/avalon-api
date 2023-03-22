<?php

namespace App\Http\Requests\Document;

use Illuminate\Foundation\Http\FormRequest;

class TransferVoucherRequest extends FormRequest
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
            'transfer_voucher' => ['nullable', 'file', 'mimes:jpg,jpeg,png'],
            'transfer_hash' => ['nullable', 'string']
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
            'transfer_voucher.file' => __('O arquivo é inválido')
        ];
    }
}
