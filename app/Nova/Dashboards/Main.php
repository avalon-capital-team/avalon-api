<?php

namespace App\Nova\Dashboards;

use App\Models\User;
use App\Models\UserPlan;
use App\Nova\Metrics\CountModel;
use App\Nova\Metrics\CountModelByDays;
use App\Nova\Metrics\User\UserCount;
use Laravel\Nova\Cards\Help;
use Laravel\Nova\Dashboards\Main as Dashboard;

class Main extends Dashboard
{

    /**
     * Get the displayable name of the metric
     *
     * @return string
     */
    public function name()
    {
        return 'Dashboard';
    }

    /**
     * Get the cards for the dashboard.
     *
     * @return array
     */
    public function cards()
    {
        return [
            (new \App\Nova\Metrics\Withdrawal\WithdrawalFiat\WithdrawalFiatPending())->width('1/2'),
            (new \App\Nova\Metrics\Withdrawal\WithdrawalCrypto\WithdrawalCryptoPending())->width('1/2'),
            (new \App\Nova\Metrics\Plan\PlanDeposited(1))->width('1/3'),
            (new \App\Nova\Metrics\Plan\PlanDeposited(2))->width('1/3'),
            (new \App\Nova\Metrics\Plan\PlanDeposited(4))->width('1/3'),
            (new \App\Nova\Metrics\Plan\PlanRent(1))->width('1/3'),
            (new \App\Nova\Metrics\Plan\PlanRent(2))->width('1/3'),
            (new \App\Nova\Metrics\Plan\PlanRent(4))->width('1/3'),
            (new \App\Nova\Metrics\Deposit\DepositFiat\DepositFiatReceiptSent())->width('1/4'),
            (new \App\Nova\Metrics\Deposit\DepositFiat\DepositFiatApproved())->width('1/4'),
            (new \App\Nova\Metrics\Deposit\DepositFiat\DepositFiatTotal())->width('1/4'),
            (new \App\Nova\Metrics\Deposit\DepositFiat\DepositFiatPartitionByStatus())->width('1/4'),
            (new CountModel(\App\Models\User::where('type', 'user'), 'Total de usuários'))->width('1/3')->icon('user-group'),
            (new CountModel(\App\Models\User::where('type', 'mananger'), 'Total de gestores'))->width('1/3')->icon('user-group'),
            (new CountModel(\App\Models\User::where('type', 'advisor'), 'Total de assessores'))->width('1/3')->icon('user-group'),
        ];
    }
}
