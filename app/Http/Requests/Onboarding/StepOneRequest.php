<?php

namespace App\Http\Requests\Onboarding;

use App\Traits\FailedValidationTrait;
use Illuminate\Foundation\Http\FormRequest;

class StepOneRequest extends FormRequest
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
            'ong_id' => ['required', 'exists:data_ongs,id'],
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
            'ong.required' => __('Selecione a ong que deseja ajudar.')
        ];
    }
}
