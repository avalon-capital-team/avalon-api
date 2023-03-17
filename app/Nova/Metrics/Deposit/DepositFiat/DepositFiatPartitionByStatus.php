<?php

namespace App\Nova\Metrics\Deposit\DepositFiat;

use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Partition;
use App\Models\Deposit\DepositFiat;
use App\Models\Deposit\DepositStatus;

class DepositFiatPartitionByStatus extends Partition
{
    /**
     * Calculate the value of the metric.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {
        return $this->sum($request, DepositFiat::class, 'amount', 'status_id')->label(function ($value) {
            $status = optional(DepositStatus::find($value));
            return $status->name;
        })->colors([
            'Aprovado' => '#4caf50',
            'Rejeitado' => '#FF9803',
            'Aguardando Pagamento' => '#f44336',
            'Comprovante enviado' => '#2196f3',
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
        return 'deposit-deposit-fiat-partition-by-status';
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
