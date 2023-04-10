<?php

namespace App\Nova\Metrics\Withdrawal\WithdrawalCrypto;

use App\Models\Withdrawal\WithdrawalCrypto;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Value;

class WithdrawalCryptoCancelled extends Value
{
    /**
     * Calculate the value of the metric.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {
        return $this->sum($request, WithdrawalCrypto::where('status_id', 1), 'amount')->format('0.000000');
    }

    /**
     * Get the ranges available for the metric.
     *
     * @return array
     */
    public function ranges()
    {
        return [
            30 => __('30 Dias'),
            60 => __('60 Dias'),
            365 => __('365 Dias'),
            'TODAY' => __('Hoje'),
            'ALL' => __('Todo o tempo'),

        ];
    }

    /**
     * Determine the amount of time the results of the metric should be cached.
     *
     * @return \DateTimeInterface|\DateInterval|float|int|null
     */
    public function cacheFor()
    {
        // return now()->addMinutes(5);
    }

    /**
     * Get the displayable name of the metric
     *
     * @return string
     */
    public function name()
    {
        return 'Saques Cancelados';
    }
}
