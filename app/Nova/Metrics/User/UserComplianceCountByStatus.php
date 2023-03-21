<?php

namespace App\Nova\Metrics\User;

use App\Models\User\UserCompliance;
use App\Models\User\UserComplianceStatus;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Value;

class UserComplianceCountByStatus extends Value
{
    /**
     * Variables.
     */
    protected $status;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct($status_id)
    {
        $this->status = UserComplianceStatus::find($status_id);
    }

    /**
     * Get the displayable name of the metric
     *
     * @return string
     */
    public function name()
    {
        return 'Total de validações ' . strtolower($this->status->name).'s';
    }

    /**
     * Calculate the value of the metric.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {
        return $this->count($request, UserCompliance::where('status_id', $this->status->id));
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
