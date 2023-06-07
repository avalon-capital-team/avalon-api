<?php

namespace App\Nova\Metrics\Plan;

use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Value;
use App\Models\Coin\Coin;
use App\Models\Plan\Plan;
use App\Models\User\UserPlan;

class PlanDeposited extends Value
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
        return 'Valor total de planos em: ' . $this->coin->symbol;
    }

    /**
     * Calculate the value of the metric.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {
        return $this->sum($request, UserPlan::where('coin_id', $this->coin_id)->where('acting', 1), 'amount')->format('0.00');
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
}
