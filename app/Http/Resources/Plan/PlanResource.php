<?php

namespace App\Http\Resources\Plan;

use App\Models\Plan\Plan;
use App\Models\User;
use App\Models\Data\DataPlan;
use App\Models\User\UserPlan;
use App\Models\Coin\Coin;
use App\Models\Credit\CreditBalance;
use App\Http\Resources\Credit\CreditResource;
use App\Http\Resources\Credit\CreditBalanceResource;


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
        $plans = Plan::where('activated_at', 'LIKE', date('Y-m-d', strtotime('-30 days')) . '%')->where('acting', 1)->get();
        foreach ($plans as $plan) {
            $this->dispatchIncomes($plan);
        }
    }

    public function dispatchIncomes(Plan $plan)
    {
        $data_plan = DataPlan::where('id', $plan->plan_id)->first();
        $coin = Coin::where('id', $plan->coin_id)->first();

        $user = User::where('id', $plan->user_id)->first();

        $income = $plan->amount * $data_plan->porcent;
        $status_id = 1;

        #gestor/acessor = 0.01;
        if ($user->sponsor_id) {
            $rent = $plan->amount * 0.01;
            $description = 'Ganho de rendimento do user: ' . $user->name;

            (new CreditResource())->create($user->sponsor_id, $plan->coin_id, $plan->id, 1, $status_id, $rent, $description);
        }

        $description = 'Rendimento mensal';

        (new CreditResource())->create($plan->user_id, $plan->coin_id, $plan->id, 1, $status_id, $income, $description);
        $balance = (new CreditBalanceResource())->getBalanceByCoinIdAndBalanceId($user);

        $balance->income += $income;

        $plan->income = $plan->income += $income;
        $user->userPlan->income = $user->userPlan->income += $income;

        $balance->save();
        $user->userPlan->save();
        $plan->save();
    }
}
