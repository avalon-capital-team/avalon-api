<?php

namespace App\Nova\Metrics;

use App\Models\Order\Order;
use App\Models\Order\OrderStatus;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Trend;

class CountModelByDays extends Trend
{
     /**
     * Variables.
     */
    protected $model_class;
    public $name;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(mixed $model_class, string $name)
    {
        $this->model_class = $model_class;
        $this->name = $name;
    }
    /**
     * Get the displayable name of the metric
     *
     * @return string
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * Calculate the value of the metric.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {
        return $this->countByDays($request, $this->model_class);
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
