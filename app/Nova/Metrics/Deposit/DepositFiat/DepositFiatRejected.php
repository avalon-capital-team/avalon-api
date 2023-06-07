<?php

namespace App\Nova\Metrics\Deposit\DepositFiat;

use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Value;
use App\Models\Deposit\DepositFiat;

class DepositFiatRejected extends Value
{
    /**
     * Calculate the value of the metric.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {
        return $this->sum($request, DepositFiat::where('status_id', 3), 'amount')->currency('R$')->format('0.00');
    }

    /**
     * Get the ranges available for the metric.
     *
     * @return array
     */
    public function ranges()
    {
        return [
          'ALL' => __('Todo o tempo'),
          'TODAY' => __('Hoje'),
            30 => __('30 Dias'),
            60 => __('60 Dias'),
            365 => __('365 Dias'),
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
        return 'Depósito rejeitados';
    }
}
