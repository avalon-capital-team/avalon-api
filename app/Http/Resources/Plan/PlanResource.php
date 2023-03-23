<?php

namespace App\Http\Resources\Plan;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Plan\Plan;
use App\Models\User;

class PlanResource
{
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
     * Create new Plan
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\Coin $coin
     * @param  int $type_id
     * @param  int $status_id
     * @param  float $amount
     * @param  string $description
     * @param  int $order_id
     * @param  bool $external
     * @param  int $plan_id
     * @param  int $transfer_user_id
     * @return \App\Models\Plan\Plan
     */
    public function create(User $user, int $user_plan_id, int $plan_id, int $coin_id, float $amount, float $income = 0.000000, bool $acting = 0, $payment_voucher_url)
    {
        $credit = Plan::create([
            'user_id' => $user->id,
            'user_plan_id' => $user_plan_id,
            'plan_id' => $plan_id,
            'coin_id' => $coin_id,
            'amount' => $amount,
            'income' => $income,
            'acting' => $acting,
            'payment_voucher_url' => $payment_voucher_url,
        ]);

        return $credit;
    }
}
