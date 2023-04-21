<?php

namespace App\Http\Resources\Credit;

use App\Models\Coin\Coin;
use App\Models\Data\DataPlan;
use App\Models\Credit\Credit;
use App\Models\User;

class CreditResource
{
    /**
     * List of credits of user w/ limit
     *
     * @param  int $user_id
     * @param  int $coin_id
     * @param  int $limit
     * @return \App\Models\Credit\Credit
     */
    public function listExtractLimited(int $user_id, int $coin_id, int $limit = 6)
    {
        return Credit::where('user_id', $user_id)
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
     * @return \App\Models\Credit\Credit
     */
    public function listExtractPaginate($userId, array $filters)
    {
        return Credit::filterSearch($filters)
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    }

    /**
     * Create new Credit
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
     * @return \App\Models\Credit\Credit
     */
    public function create($user_id, int $coin_id, int $plan_id = 0, int $type_id = 1, int $status_id = 2, float $amount, float $base_amount = 0, string $description = '', int $order_id = null)
    {
        $credit = Credit::create([
            'user_id' => $user_id,
            'coin_id' => $coin_id,
            'plan_id' => $plan_id,
            'type_id' => $type_id,
            'status_id' => $status_id,
            'description' => $description,
            'amount' => $amount,
            'base_amount' => $base_amount,
            'order_id' => $order_id,
            'transfer_user_id' => null,
            'external' => null
        ]);

        return $credit;
    }
}
