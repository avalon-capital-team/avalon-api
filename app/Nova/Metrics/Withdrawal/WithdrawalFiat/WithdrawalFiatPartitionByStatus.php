<?php

namespace App\Nova\Metrics\Withdrawal\WithdrawalFiat;

use App\Models\Withdrawal\WithdrawalStatus;
use App\Models\Withdrawal\WithdrawalFiat;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Partition;

class WithdrawalFiatPartitionByStatus extends Partition
{
    /**
     * Calculate the value of the metric.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {
        return $this->sum($request, WithdrawalFiat::class, 'amount', 'status_id')->label(function ($value) {
            $status = optional(WithdrawalStatus::find($value));
            return $status->name;
        })->colors([
            'Aprovado' => '#4caf50',
            'Pendente' => '#FF9803',
            'Cancelado' => '#f44336',
        ]);
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
     * Get the URI key for the metric.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'withdrawal-withdrawal-fiat-withdrawal-fiat-partition-by-status';
    }

    /**
     * Get the displayable name of the metric
     *
     * @return string
     */
    public function name()
    {
        return 'Gráfico por status';
    }
}
