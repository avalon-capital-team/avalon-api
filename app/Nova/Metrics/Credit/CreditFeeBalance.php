<?php

namespace App\Nova\Metrics\Credit;

use App\Models\Coin\Coin;
use App\Models\Fee\FeeHistory;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Value;

class CreditFeeBalance extends Value
{
    /**
      * Variables.
      */
    protected $coin_id;
    protected $coin;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct($coin_id)
    {
        $this->coin_id = $coin_id;
        $this->coin = Coin::find($coin_id);
    }

    /**
     * Get the displayable name of the metric
     *
     * @return string
     */
    public function name()
    {
        return 'Saldo em taxas de: ' . $this->coin->symbol;
    }

    /**
     * Calculate the value of the metric.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {
        return $this->sum($request, FeeHistory::where('coin_id', $this->coin_id)->where('status', 1), 'amount')->format('0.00');
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
}
