<?php

namespace App\Rules;

use App\Http\Resources\System\Rules\LimitOfUserResource;
use Illuminate\Contracts\Validation\Rule;

class CheckLimitWithdrawalRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->user = auth()->user();
        $this->limit = (new LimitOfUserResource())->checkLimits($this->user)['withdrawal'];
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return ($value <= $this->limit) ? true : false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('O valor é maior que o seu limite disponível.');
    }
}
