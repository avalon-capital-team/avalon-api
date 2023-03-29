<?php

namespace App\Http\Resources\Plan;

use App\Models\Plan\Plan;
use App\Models\User;
use App\Models\Data\DataPlan;
use App\Models\User\UserPlan;
use App\Http\Resources\Credit\CreditResource;
use App\Http\Resources\Credit\CreditBalanceResource;

class PlanResource
{
    /**
     * List of plans of user
     *
     * @param  int $user_id
     * @param  int $coin_id
     * @return \App\Models\Plan\Plan
     */
    public function listPlan(int $user_id, int $plan_id)
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
        $user_plan = UserPlan::where('user_id', $plan->user_id)->first();
        $user = User::where('id', $plan->user_id)->select('sponsor_id', 'name')->first();

        $income = $plan->amount * $data_plan->porcent;
        $status_id = 1;

        #gestor/acessor = 0.01;
        if ($user->sponsor_id) {
            $rent = $user_plan->amount * 0.01;
            $description = 'Ganho de rendimento do user: ' . $user->name;

            (new CreditResource())->create($plan->user_id, $plan->coin_id, $plan->id, 1, $status_id, $rent, $description);
        }

        $description = 'Rendimento mensal';

        (new CreditResource())->create($plan->user_id, $plan->coin_id, $plan->id, 1, $status_id, $income, $description);

        $plan->income = $plan->income += $income;
        $user_plan->income = $user_plan->income += $income;

        $user_plan->save();
        $plan->save();
    }
}
