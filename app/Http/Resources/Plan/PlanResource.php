<?php

namespace App\Http\Resources\Plan;

use App\Models\Plan\Plan;
use App\Models\User;
use App\Models\Data\DataPlan;
use App\Http\Resources\Credit\CreditResource;
use App\Http\Resources\Credit\CreditBalanceResource;
use DateTime;


class PlanResource
{
    /**
     * List of plans of user
     *
     * @param  int $user_id
     * @return \App\Models\Plan\Plan
     */
    public function listPlans(int $user_id)
    {
        return Plan::where('user_id', $user_id)
            ->select(
                'acting',
                'token',
                'amount',
                'income',
                'activated_at'
            )
            ->get();
    }

    /**
     * List of plans of user in acting
     *
     * @param  int $user_id
     * @return \App\Models\Plan\Plan
     */
    public function listActingPlans(int $user_id)
    {
        return Plan::where('user_id', $user_id)
            ->where('acting', 1)
            ->sum('amount')
            ->get();
    }

    /**
     * Get plan of user
     *
     * @param  int $user_id
     * @param  int $plan_id
     * @return \App\Models\Plan\Plan
     */
    public function getPlan(int $user_id, int $plan_id)
    {
        return Plan::where('user_id', $user_id)
            ->where('id', $plan_id)
            ->first();
    }

    /**
     * List of credits of user w/ limit
     *
     * @param  int $user_id
     * @param  int $coin_id
     * @param  int $limit
     * @return \App\Models\Plan\Plan
     */
    public function listExtractLimited(int $user_id, int $coin_id, int $limit = 6)
    {
        return Plan::where('user_id', $user_id)
            ->where('coin_id', $coin_id)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * List of credits of user w/ filter
     *
     * @param  int $userId
     * @param  array $filters
     * @return \App\Models\Plan\Plan
     */
    public function listExtractPaginate($userId, array $filters)
    {
        return Plan::filterSearch($filters)
            ->where('user_id', $userId)
            ->with('plan', 'coin', 'type')
            ->orderBy('created_at', 'desc')
            ->paginate(13);
    }

    /**
     * Check if need pay installments
     *
     * @return void
     */
    public function checkIfNeedPayToday()
    {

        $plans = Plan::where('acting', 1)->get();

        foreach ($plans as $plan) {
            $dateInterval = $this->dateInterval(date('Y-m-d', strtotime($plan->activated_at)), date('Y-m-t'));
            $this->dispatchIncomes($plan, $dateInterval);
        }
    }

    /**
     * Check a days installments
     *
     * @param  string $date_from
     * @param  string $date_to
     * @return string
     */
    function dateInterval($date_from, $date_to)
    {
        $date_from = new DateTime($date_from);
        $date_to = new DateTime($date_to);

        // Redeem the difference between the dates
        $dateInterval = $date_from->diff($date_to);
        return $dateInterval->days + 1;
    }


    public function dispatchIncomes(Plan $plan, $dateInterval)
    {
        $data_plan = DataPlan::where('id', $plan->plan_id)->first();
        $user = User::where('id', $plan->user_id)->first();

        $date_from = date('Y-m-t');
        $date_to = date('Y-m-' . '01');

        $days = $this->dateInterval($date_to, $date_from);

        $percent = $data_plan->porcent / $days;
        $percentPeriodo = $dateInterval * $percent;
        $income = $plan->amount * $percentPeriodo;

        $status_id = 1;

        # Acessor/ Gestor = 0.01;
        if ($user->sponsor_id) {
            $rent = $plan->amount * 0.01;
            $description = 'Ganho de rendimento do user: ' . $user->name;

            (new CreditResource())->create($user->sponsor_id, $plan->coin_id, 4, $status_id, $rent, 0.000000, $description);
            $user_sponsor = User::where('id', $user->sponsor_id)->first();

            $balance_sponsor = (new CreditBalanceResource())->getBalanceByCoinIdAndBalanceId($user_sponsor);
            $balance_sponsor = $user_sponsor->creditBalance;
            $balance_sponsor->income += $income;
            $balance_sponsor->save();
        }

        $description = 'Rendimento mensal';
        (new CreditResource())->create($plan->user_id, $plan->coin_id, $plan->id, 3, $status_id, $income, $plan->amount,  $description);

        $balance = (new CreditBalanceResource())->getBalanceByCoinIdAndBalanceId($user);

        $balance->income += $income;

        $plan->income = $plan->income += $income;
        $user->userPlan->income = $user->userPlan->income += $income;

        $balance->save();
        $user->userPlan->save();
        $plan->save();
    }
}
