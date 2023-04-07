<?php

namespace App\Http\Resources\Credit;

use App\Http\Resources\Coin\CoinResource;
use App\Models\Credit\CreditBalance;
use App\Models\User;
use App\Models\Plan\Plan;
use App\Models\Coin\Coin;
use App\Models\Credit\Credit;
use Illuminate\Support\Facades\DB;

class CreditBalanceResource
{
    /**
     * Get balance of user logged by CoinID
     *
     * @param  \App\Models\User $user
     * @param  int $coinId
     * @param  array $filters
     * @return float
     */
    public function getGraphicData(User $user, int $coinId = 1, array $filters)
    {
        $coin = (new CoinResource())->findById($coinId);
        $plan['total'] = Plan::where('user_id', $user->id)->where('acting', 1)->sum('amount');
        $plan['income'] = Plan::where('user_id', $user->id)->where('acting', 1)->sum('income');

        $credits = Credit::where('user_id', $user->id)
            ->where('type_id', 3)
            ->filterSearch($filters)
            ->orderBy('created_at', 'desc')
            ->select('amount', 'base_amount', 'created_at')
            ->get();

        $creditBalance = $this->checkBalanceByCoinId($user, $coin);

        if (count($credits) == 0) {
            $monthData = [
                'amount' => 0,
                'base_amount' => 0,
                'month' => null
            ];
        } else {
            // dd($credits);
            $monthData = $this->towerChart($user, $credits);
        }

        $balance_total = $plan['total'] + $creditBalance->income + floatval(str_replace('-', '', $creditBalance->used));
        if (!$balance_total == 0.0) {
            $balance_placed = $plan['total'] * 100 / $balance_total;
            $balance_rendeem = floatval(str_replace('-', '', $creditBalance->used)) * 100 / $balance_total;
            $balance_income = $creditBalance->income * 100 / $balance_total;
        } else {
            $balance_placed = 0;
            $balance_rendeem = 0;
            $balance_income = 0;
        }

        $data = [
            'balance_enable' => $creditBalance->balance_enable,
            'balance_pending' => $creditBalance->balance_pending,
            'balance_placed' => $plan['total'],
            'balance_rendeem' => $creditBalance->used,
            'balance_income' => $creditBalance->income,
            'pie_chart' => [
                'placed' => $balance_placed,
                'rendeem' => $balance_rendeem,
                'income' => $balance_income
            ],
            'chart' => $monthData,
        ];

        return $data;
    }

    /**
     * Get a sum per month
     *
     * @param  \App\Models\User $user
     * @return float
     */
    public function towerChart(User $user, $credits)
    {
        $i = 0;
        $monthData = [];
        foreach ($credits as $credit) {
            $initialMonth = date('Y', strtotime($credit['created_at'])) . '-' . date('m', strtotime($credit['created_at'])) . '-' .  '01';
            $finalMonth = date('Y-m-t', strtotime($credit['created_at']));

            $filters = [
                'date_from' => $initialMonth,
                'date_to' => $finalMonth
            ];
            $monthSelected = $this->sumBalanceMonth($user, $filters);
            $monthSelected = $this->validateMonth($monthData);

            $monthSelected['month'] = $credit['created_at'];
            $monthData[++$i] = $monthSelected;
        }

        return $monthData;
    }

    /**
     * Perform the sum of credit and base
     *
     * @param  \App\Models\User $user
     * @param  array $filters
     * @return float
     */
    public function validateMonth(array $monthData)
    {
        dd($monthData);

        return true;
    }

    /**
     * Perform the sum of credit and base
     *
     * @param  \App\Models\User $user
     * @param  array $filters
     * @return float
     */
    public function sumBalanceMonth(User $user, array $filters)
    {
        $credit['amount'] = Credit::where('user_id', $user->id)
            ->where('type_id', 3)
            ->filterSearch($filters)
            ->sum('amount');

        $credit['base_amount'] = Credit::where('user_id', $user->id)
            ->where('type_id', 3)
            ->filterSearch($filters)
            ->sum('base_amount');

        return $credit;
    }

    /**
     * Get balance of user logged by CoinID
     *
     * @param  \App\Models\User $user
     * @param  int $coinId
     * @return float
     */
    public function getBalanceByCoinId(User $user, int $coinId)
    {
        $coin = (new CoinResource())->findById($coinId);
        $creditBalance = $this->checkBalanceByCoinId($user, $coin);
        return ($creditBalance) ? $creditBalance->balance_enable : 0;
    }

    /**
     * Get balances of user logged by CoinID
     *
     * @param  \App\Models\User $user
     * @param  int $coinId
     * @return array
     */
    public function getBalancesByCoinId(User $user, int $coinId)
    {
        return $user->creditBalance()->where('coin_id', $coinId)->get();
    }

    /**
     * Get balance of user with Balance Id and Coin Id
     *
     * @param  \App\Models\User $user
     * @param  int $coinId
     * @param  int $balanceId
     * @return \App\Models\Credit\CreditBalance
     */
    public function getBalanceByCoinIdAndBalanceId(User $user)
    {

        return $user->creditBalance()->where('user_id', $user->id)->first();
    }

    /**
     * Move Balance to pending
     *
     * @param \App\Models\Credit\CreditBalance $credit
     */
    public function moveBalanceToPending(CreditBalance $creditBalance, float $amount)
    {
        $creditBalance->balance_enable -= $amount;
        $creditBalance->balance_pending += $amount;
        $creditBalance->save();
    }

    /**
     * Move Balance to pending
     *
     * @param \App\Models\Credit\CreditBalance $credit
     */
    public function moveBalanceToEnable(CreditBalance $creditBalance, float $amount)
    {
        $creditBalance->balance_pending -= $amount;
        $creditBalance->balance_enable += $amount;
        $creditBalance->save();
    }

    /**
     * Check balance of user by CoinID
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\Coin\Coin $coin
     * @return \App\Models\Credit\CreditBalance
     */
    public function checkBalanceByCoinId(User $user, Coin $coin)
    {
        $balance = CreditBalance::where('user_id', $user->id)->where('coin_id', $coin->id)->first();
        if (!$balance) {
            $user->creditBalance()->create(['coin_id' => $coin->id]);
            $balance =  CreditBalance::where('user_id', $user->id)->where('coin_id', $coin->id)->first();
        }

        return $balance;
    }

    /**
     * @param  arrey $data
     * @return bool
     */
    public function inativePlan($data)
    {
        $coin = (new CoinResource())->findById($data['coin_id']);
        $user = User::where('id', ($data['user_id']))->first();

        $user->userPlan->amount -= $data['amount'];

        $balance = $this->checkBalanceByCoinId($user, $coin);
        $balance['balance_enable'] -= $data->amount;
        $balance['balance_pending'] += $data->amount;

        $user->userPlan->save();

        return $balance->save();
    }

    /**
     * @param  arrey $data
     * @return bool
     */
    public function updateBalance($data)
    {
        $coin = (new CoinResource())->findById($data['coin_id']);
        $user = User::where('id', ($data['user_id']))->first();

        $user->userPlan->amount = $data['amount'];

        $balance = $this->checkBalanceByCoinId($user, $coin);
        $balance['balance_pending'] -= $data->amount;
        $balance['balance_enable'] += $data->amount;

        $user->userPlan->save();

        return $balance->save();
    }

    /**
     * @param  \App\Models\User $user
     * @param  int $coin_id
     * @param  float $amount
     * @return bool
     */
    public function createOrUpdateBalance(User $user, int $coin_id, float $amount, int $plan_id)
    {
        $coin = (new CoinResource())->findById($coin_id);
        $balance = $this->checkBalanceByCoinId($user, $coin);

        if (!$balance) {
            $balance = new CreditBalance();
            $balance->user_id = $user->id;
            $balance->coin_id = $coin_id;
        }
        $balance->plan_id = $plan_id;
        $balance->balance_pending += $amount;

        if ($amount > 0) {
            $balance->deposited += $amount;
        } else {
            $balance->used += $amount;
        }

        return $balance->save();
    }

    /**
     * @param  \App\Models\User $user
     * @param  int $coin_id
     * @param  float $amount
     * @param  string $field
     * @return bool
     */
    public function updateField(User $user, int $coin_id, float $amount, string $field)
    {
        $coin = (new CoinResource())->findById($coin_id);
        $balance = $this->checkBalanceByCoinId($user, $coin);

        if (!$balance) {
            $balance = new CreditBalance();
            $balance->user_id = $user->id;
            $balance->coin_id = $coin_id;
        }

        $balance->$field += $amount;
        return $balance->save();
    }


    /**
     * Get balances of wallets
     *
     * @param  \App\Models\User $user
     * @param  int $coinId
     * @return \App\Models\Credit\CreditBalance
     */
    public function getBalancesSales(User $user, int $coinId)
    {
        return CreditBalance::where('user_id', $user->id)
            ->where('coin_id', $coinId)
            ->whereNotNull('token_sale_id')
            ->with('coin')
            ->get();
    }

    /**
     * Get balances of brl with average
     *
     * @param  \App\Models\User $user
     * @return array
     */
    public function getInfoConvertedBrl(User $user)
    {
        $coinBrl = (new CoinResource())->findBySymbol('BRL');

        $data['balance'] = CreditBalance::where('user_id', $user->id)
            ->where('coin_id', $coinBrl->id)
            ->sum('balance_enable');

        $data['average'] = CreditBalance::where('user_id', $user->id)
            ->join('coins', 'coins.id', '=', 'credits_balance.coin_id')
            ->sum(DB::raw('credits_balance.balance_enable * coins.price_brl'));

        return $data;
    }
}
