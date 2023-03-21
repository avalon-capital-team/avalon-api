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
            (new CountModelByDays(\App\Models\User::where('type', 'user'), 'Usuários por dia'))->width('1/2'),
            (new CountModel(\App\Models\User::where('type', 'user'), 'Total de usuários'))->width('1/3')->icon('user-group'),
            (new \App\Nova\Metrics\Order\TotalSaleAll())->width('1/3'),
            (new \App\Nova\Metrics\Order\SalesPerDay())->width('1/2'),
            // (new \App\Nova\Metrics\Deposit\DepositFiat\DepositFiatApproved())->width('1/4'),
            // (new \App\Nova\Metrics\Deposit\DepositFiat\DepositFiatTotal())->width('1/4'),
        ];
    }
}
