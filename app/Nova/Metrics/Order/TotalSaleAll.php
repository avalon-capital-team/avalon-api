<?php

namespace App\Nova\Metrics\Order;

use App\Models\Order\Order;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Value;

class TotalSaleAll extends Value
{
    /**
     * Get the displayable name of the metric
     *
     * @return string
     */
    public function name()
    {
        return 'Total dos Aportes';
    }

    /**
     * Calculate the value of the metric.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {
        return $this->sum($request, Order::class, 'total')->prefix('R$')->format('0,0.00');
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
            'MTD' => __('Neste mês'),
            'QTD' => __('Semestre'),
            'YTD' => __('Ano'),
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
}
