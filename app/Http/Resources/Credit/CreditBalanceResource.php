<?php

namespace App\Http\Resources\Credit;

use App\Http\Resources\Coin\CoinResource;
use App\Models\Credit\CreditBalance;
use App\Models\User;
use App\Models\Plan\Plan;
use App\Models\Coin\Coin;
use App\Models\Credit\Credit;
use App\Http\Resources\Plan\PlanResource;
use Illuminate\Support\Facades\DB;
use Log;
use Carbon\Carbon;

class CreditBalanceResource
{
    /**
     * Get balance of user logged by CoinID
     *
     * @param  \App\Models\User $user
     * @param  int $coinId
     * @return float
     */
    public function getGraphicData(User $user, int $coinId = 1)
    {
        $coin = (new CoinResource())->findById($coinId);
        $plans['total'] = Plan::where('user_id', $user->id)->where('acting', 1)->sum('amount');
        $plans['income'] = Plan::where('user_id', $user->id)->where('acting', 1)->sum('income');

        $plans['list'] = Credit::where('user_id', $user->id)->where('created_at', 'LIKE', date('Y-m-d', strtotime('-6 months')) . '%')->select('amount', 'created_at')->get();
        $creditBalance = $this->checkBalanceByCoinId($user, $coin);


        // $datai = date('Y-m-d', strtotime('-6 months'));
        // $lastDayMonth = date('t');
        // dd($lastDayMonth, $datai, $plans);

        // foreach ($plans['list'] as $credit) {
        //     $month = date('n', strtotime($credit['created_at']));
        //     if ($month == 1) {
        //         $month['month'] = 'January';
        //         $month['amount'] = $credit['amount'] ? $credit['amount'] : 0.000000;
        //         $month['income'] = 0.000000;
        //     } else if ($month == 2) {
        //         $month['month'] = 'February';
        //         $month['amount'] = $credit['amount'] ? $credit['amount'] : 0.000000;
        //         $month['income'] = 0.000000;
        //     } else if ($month == 3) {
        //         $month['month'] = 'March';
        //         $month['amount'] = $credit['amount'] ? $credit['amount'] : 0.000000;
        //         $month['income'] = 0.000000;
        //     } else if ($month == 4) {
        //         $month['month'] = 'April';
        //         $month['amount'] = $credit['amount'] ? $credit['amount'] : 0.000000;
        //         $month['income'] = 0.000000;
        //     } else if ($month == 5) {
        //         $month['month'] = 'May';
        //         $month['amount'] = $credit['amount'] ? $credit['amount'] : 0.000000;
        //         $month['income'] = 0.000000;
        //     } else if ($month == 6) {
        //         $month['month'] = 'June';
        //         $month['amount'] = $credit['amount'] ? $credit['amount'] : 0.000000;
        //         $month['income'] = 0.000000;
        //     } else if ($month == 7) {
        //         $month['month'] = 'July';
        //         $month['amount'] = $credit['amount'] ? $credit['amount'] : 0.000000;
        //         $month['income'] = 0.000000;
        //     } else if ($month == 8) {
        //         $month['month'] = 'August';
        //         $month['amount'] = $credit['amount'] ? $credit['amount'] : 0.000000;
        //         $month['income'] = 0.000000;
        //     } else if ($month == 9) {
        //         $month['month'] = 'September';
        //         $month['amount'] = $credit['amount'] ? $credit['amount'] : 0.000000;
        //         $month['income'] = 0.000000;
        //     } else if ($month == 10) {
        //         $month['month'] = 'October';
        //         $month['amount'] = $credit['amount'] ? $credit['amount'] : 0.000000;
        //         // $oct['income'] = $credit['created_at'];
        //     } else if ($month == 11) {
        //         $month['month'] = 'November';
        //         $month['amount'] = $credit['amount'] ? $credit['amount'] : 0.000000;
        //         // $nov['income'] = $credit['created_at'];
        //     } else if ($month == 12) {
        //         $month['month'] = 'December';
        //         $month['amount'] = $credit['amount'] ? $credit['amount'] : 0.000000;
        //         $month['income'] = 0.000000;
        //     }
        //     // dd($month);
        // }





        $balance_total = $plans['total'] + $creditBalance->income + floatval(str_replace('-', '', $creditBalance->used));

        $balance_placed = $plans['total'] * 100 / $balance_total;
        $balance_rendeem = floatval(str_replace('-', '', $creditBalance->used)) * 100 / $balance_total;
        $balance_income = $creditBalance->income * 100 / $balance_total;

        $data = [
            'balance_enable' => $creditBalance->balance_enable,
            'balance_pending' => $creditBalance->balance_pending,
            'balance_placed' => $plans['total'],
            'balance_rendeem' => $creditBalance->used,
            'balance_income' => $creditBalance->income,
            'pie_chart' => [
                'placed' => $balance_placed,
                'rendeem' => $balance_rendeem,
                'income' => $balance_income
            ],
            'tower_chart' => $plans['list'],

        ];

        return $data;
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
    public function updateBalance($data)
    {
        $coin = (new CoinResource())->findById($data['coin_id']);
        $user = User::where('id', ($data['user_id']))->first();

        $balance = $this->checkBalanceByCoinId($user, $coin);
        $balance['balance_pending'] -= $data->amount;
        $balance['balance_enable'] += $data->amount;

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
