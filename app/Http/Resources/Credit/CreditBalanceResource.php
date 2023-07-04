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

    $credits = Credit::where('user_id', $user->id)
      ->where('type_id', 3)
      ->filterSearch($filters)
      ->orderBy('created_at', 'asc')
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
      $monthData = $this->towerChart($user, $credits);
    }

    $balance_total = $creditBalance->balance_placed + $creditBalance->income + floatval(str_replace('-', '', $creditBalance->withdrawal));
    if (!$balance_total == 0.0) {
      $balance_placed = $creditBalance->balance_placed * 100 / $balance_total;
      $balance_rendeem = floatval(str_replace('-', '', $creditBalance->withdrawal)) * 100 / $balance_total;
      $balance_income = $creditBalance->income * 100 / $balance_total;
    } else {
      $balance_placed = 0;
      $balance_rendeem = 0;
      $balance_income = 0;
    }

    $data = [
      'balance_enable' => $creditBalance->balance_enable,
      'balance_placed' => $creditBalance->balance_placed,
      'balance_pending' => $creditBalance->balance_pending,
      'balance_rendeem' => $creditBalance->withdrawal,
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
    foreach ($credits as $credit) {
      $initialMonth = date('Y', strtotime($credit['created_at'])) . '-' . date('m', strtotime($credit['created_at'])) . '-' .  '01';
      $finalMonth = date('Y-m-t', strtotime($credit['created_at']));

      $filters = [
        'date_from' => $initialMonth,
        'date_to' => $finalMonth
      ];

      $monthSelected = $this->sumBalanceMonth($user, $filters);

      $monthSelected['month'] = $credit['created_at'];
      $monthData[++$i] = $monthSelected;
    }

    return $monthData;
  }

  /**
   * Get a sum per month
   *
   * @param  \App\Models\User $user
   * @return float
   */
  public function reportData(User $user)
  {
    $credits_type_2 = Credit::where('user_id', $user->id)
      ->where('type_id', 2)
      ->select(
        DB::raw('SUM(amount) as redeem'),
        DB::raw('SUM(base_amount) as redeem_base_amount'),
        DB::raw('MONTH(created_at) as month'),
        DB::raw('YEAR(created_at) as year')
      )
      ->groupBy(DB::raw('YEAR(created_at)'), DB::raw('MONTH(created_at)'))
      ->orderBy(DB::raw('YEAR(created_at)'), 'asc')
      ->orderBy(DB::raw('MONTH(created_at)'), 'asc')
      ->get()
      ->groupBy(function ($item) {
        return $item['year'] . '-' . str_pad($item['month'], 2, '0', STR_PAD_LEFT);
      });

    $credits_type_3 = Credit::where('user_id', $user->id)
      ->where('type_id', 3)
      ->select(
        DB::raw('SUM(amount) as amount'),
        DB::raw('SUM(base_amount) as base_amount'),
        DB::raw('MONTH(created_at) as month'),
        DB::raw('YEAR(created_at) as year')
      )
      ->groupBy(DB::raw('YEAR(created_at)'), DB::raw('MONTH(created_at)'))
      ->orderBy(DB::raw('YEAR(created_at)'), 'asc')
      ->orderBy(DB::raw('MONTH(created_at)'), 'asc')
      ->get()
      ->groupBy(function ($item) {
        return $item['year'] . '-' . str_pad($item['month'], 2, '0', STR_PAD_LEFT);
      });

    $credits = [];

    foreach ($credits_type_2 as $date => $data) {
      $credits[$date] = [
        'date' => $date,
        'redeem' => $data[0]['redeem'],
        'redeem_base_amount' => $data[0]['redeem_base_amount'],
        'amount' => isset($credits_type_3[$date]) ? $credits_type_3[$date][0]['amount'] : 0,
        'base_amount' => isset($credits_type_3[$date]) ? $credits_type_3[$date][0]['base_amount'] : 0,
      ];
    }

    foreach ($credits_type_3 as $date => $data) {
      if (!isset($credits[$date])) {
        $credits[$date] = [
          'date' => $date,
          'redeem' => 0,
          'redeem_base_amount' => 0,
          'amount' => $data[0]['amount'],
          'base_amount' => $data[0]['base_amount'],
        ];
      }
    }
    ksort($credits);
    return $credits;
  }

  public function sumBalanceMonth(User $user, array $filters)
  {
    $credit['rendeem'] = Credit::where('user_id', $user->id)
      ->where('type_id', 2)
      ->filterSearch($filters)
      ->sum('amount');

    $credit['amount'] = Credit::where('user_id', $user->id)
      ->where('type_id', 3)
      ->filterSearch($filters)
      ->sum('amount');

    $credit['base_amount'] = Credit::where('user_id', $user->id)
      ->where('type_id', 3)
      ->filterSearch($filters)
      ->sum('base_amount');

    $credit['rendeem'] = floatval(str_replace('-', '', $credit['rendeem']));

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
    return ($creditBalance) ? $creditBalance->balance_placed : 0;
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
  public function getBalances(User $user)
  {
    return $user->creditBalance()->where('user_id', $user->id)->get();
  }

  /**
   * Get balance of user with Balance Id and Coin Id
   *
   * @param  \App\Models\User $user
   * @param  int $coinId
   * @param  int $balanceId
   * @return \App\Models\Credit\CreditBalance
   */
  public function getBalanceByCoinIdAndBalanceId(User $user, $coin_id)
  {
    return $user->creditBalance()->where('user_id', $user->id)->where('coin_id', $coin_id)->first();
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
   * Move Balance to pending
   *
   * @param \App\Models\Credit\CreditBalance $credit
   */
  public function moveBalanceToPlaced(CreditBalance $creditBalance, float $amount)
  {
    $creditBalance->balance_pending -= $amount;
    $creditBalance->balance_placed += $amount;
    $creditBalance->save();
  }

  /**
   * Move Balance to pending
   *
   * @param \App\Models\Credit\CreditBalance $credit
   */
  public function moveBalanceToIncome(CreditBalance $creditBalance, float $amount)
  {
    $creditBalance->balance_placed -= $amount;
    $creditBalance->balance_pending += $amount;
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
      $user->creditBalance()->create(['coin_id' => $coin->id, 'show_wallet' => 1]);
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
    $balance['balance_placed'] -= $data->amount;
    $balance['balance_pending'] += $data->amount;

    $user->userPlan->save();

    return $balance->save();
  }

  /**
   * @param  arrey $data
   * @return bool
   */
  public function approveBalance($data)
  {
    $coin = (new CoinResource())->findById($data['coin_id']);
    $user = User::where('id', ($data['user_id']))->first();

    $user->userPlan->amount += $data['amount'];
    $user->userPlan->acting = 1;

    $balance = $this->checkBalanceByCoinId($user, $coin);
    $balance['balance_pending'] -= $data->amount;
    $balance['balance_placed'] += $data->amount;

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
    $balance->plan_id = $plan_id;
    $balance->coin_id = $coin_id;
    $balance->balance_pending += $amount;

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
      ->sum('balance_placed');

    $data['average'] = CreditBalance::where('user_id', $user->id)
      ->join('coins', 'coins.id', '=', 'credits_balance.coin_id')
      ->sum(DB::raw('credits_balance.balance_placed * coins.price_brl'));

    return $data;
  }
}
