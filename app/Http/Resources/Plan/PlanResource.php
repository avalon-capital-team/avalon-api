<?php

namespace App\Http\Resources\Plan;

use App\Models\Plan\Plan;
use App\Models\User;
use App\Models\Data\DataPlan;
use App\Models\Coin\Coin;
use App\Http\Resources\Credit\CreditResource;
use App\Http\Resources\Credit\CreditBalanceResource;
use App\Jobs\Credit\MoveBalanceToEnableJob;
use App\Models\Data\DataPercent;
use App\Models\User\UserPlan;
use App\Nova\Models\Coin\Coin as CoinCoin;
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
    $plans = Plan::where('user_id', $user_id)
      ->select(
        'id',
        'acting',
        'plan_id',
        'coin_id',
        'payment_voucher_url',
        'payment_method_id',
        'withdrawal_report',
        'token',
        'amount',
        'income',
        'activated_at'
      )
      ->get();

    foreach ($plans as $plan) {
      $coin = Coin::where('id', $plan['coin_id'])->first();
      $plan['converted_amount'] = $plan['amount'] / $coin->price_brl;
    }

    return $plans;
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
      $this->dispatchIncomes($plan);
    }
    return $plans;
  }

  /**
   *
   * @return bool
   */
  function reverseWithdrawalPlan(User $user, $amount)
{
    $plans = Plan::where('user_id', $user->id)->get();
    $user_plan = $user->userPlan;

    if ($amount > $user_plan->income) {
        throw new \Exception('Não foi possível liberar o valor. Tente novamente mais tarde!', 403);
    }

    if ($user_plan->income == 0) {
        throw new \Exception('O rendimento do plano do usuário é zero, divisão não permitida!', 403);
    }

    foreach ($plans as $plan) {
        $data['percet'] = $plan->income / $user_plan->income;
        $data['amount'] = $data['percet'] * $amount;
        $data['plan'] = $plan->income;
        $data['userplan'] = $user_plan->income;

        $plan->income += $data['amount'];
        $plan->save();
    }
    $user->userPlan->income += $amount;
    $user->userPlan->save();

    return true;
}


  /**
   *
   * @return bool
   */
  function withdrawalPlan(User $user, $amount)
  {
    $plans = Plan::where('user_id', $user->id)->get();
    $user_plan = $user->userPlan;

    if ($amount > $user_plan->income) {
      throw new \Exception('Não foi possível liberar o valor. Tente novamente mais tarde!', 403);
    }

    foreach ($plans as $plan) {
      $data['percet'] = $plan->income / $user_plan->income;
      $data['amount'] = $data['percet'] * $amount;
      $data['plan'] = $plan->income;
      $data['userplan'] = $user_plan->income;

      $plan->income -= $data['amount'];
      $plan->save();
    }
    $user->userPlan->income -= $amount;
    $user->userPlan->save();

    return true;
  }

  /**
   *
   * @return bool
   */
  function updataPlan(User $user, $data)
  {
    $plans = Plan::where('user_id', $user->id)->get();

    foreach ($plans as $plan) {
      $plan->plan_id = $data;
      $plan->save();
    }
    $user->userPlan->plan_id = $data;
    $user->userPlan->save();

    return true;
  }

  /**
   *
   * @return bool
   */
  function dateInterval($date_from, $date_to)
  {
    $date_from = new DateTime($date_from);
    $date_to = new DateTime($date_to);

    // Redeem the difference between the dates
    $dateInterval = $date_from->diff($date_to);
    return $dateInterval->days + 1;
  }

  /**
   *
   * User
   * @param  bool $value
   * @return string
   */
  function getAutomaticReport(User $user)
  {
    $auto_report = Plan::where('user_id', $user->id)->where('acting', 1)->select('withdrawal_report')->first();

    if (!$auto_report) return false;
    if ($auto_report->withdrawal_report == 0) return false;
    return true;
  }

  /**
   *
   * User
   * @param  bool $value
   * @return string
   */
  function withdralReport($user_id, bool $value)
  {
    $planCount = Plan::where('user_id', $user_id)
      ->where('acting', 1)
      ->count();

    if ($planCount === 0) {
      return false;
    }

    UserPlan::where('user_id', $user_id)
      ->update(['withdrawal_report' => $value]);

    Plan::where('user_id', $user_id)
      ->where('acting', 1)
      ->update(['withdrawal_report' => $value]);

    return true;
  }

  public function dispatchIncomes(Plan $plan)
  {
    $data_plan = DataPlan::where('id', $plan->plan_id)->first();
    $coin = Coin::where('id', $plan->coin_id)->first();
    $user = User::where('id', $plan->user_id)->first();

    $status_id = 1;
    $income = $this->calculePercent($plan, $data_plan);

    if ($user->sponsor_id) {
      $sponsor = User::where('id', $user->sponsor_id)->first();

      if ($sponsor->type == 'manange' || $sponsor->type == 'advisor') {
        $percent = DataPercent::where('tag', $sponsor->type)->first();
        $rent = $this->calculePercent($plan, $data_plan, $percent);

        $description = 'Ganho de rendimento do user: ' . $user->name;
        (new CreditResource())->create($plan->user_id, $plan->coin_id, $plan->id, 4, $status_id, floatval($rent), 0, $description);

        $balance_sponsor = (new CreditBalanceResource())->checkBalanceByCoinId($sponsor, $coin);
        (new CreditBalanceResource())->moveBalanceToEnable($balance_sponsor, $rent);
        $balance_sponsor->income += $rent;
        $balance_sponsor->save();
      }
    }

    $balance = (new CreditBalanceResource())->checkBalanceByCoinId($user, $coin);

    $description = 'Rendimento mensal - usuário:' . $user->name;
    (new CreditResource())->create($plan->user_id, $plan->coin_id, $plan->id, 3, $status_id, floatval($income), floatval($balance->balance_placed),  $description);

    $balance->income += $income;

    if ($plan->withdrawal_report == 0) {
      // MoveBalanceToEnableJob::dispatch($balance, $income);
      (new CreditBalanceResource())->moveBalanceToEnable($balance, $income);
    } else {
      (new CreditBalanceResource())->moveBalanceToPlaced($balance, $income);
    }

    $user->userPlan->income += $income;

    $plan->income += $income;

    $balance->save();
    $user->userPlan->save();
    $plan->save();
  }

  /**
   * Execute calcule of a percent
   *
   * @param   $plan
   * @param   $data_plan
   * @return
   */
  function calculePercent($plan, $data_plan, $sponsor = null)
  {
    $date_from = date('Y-m-t');
    $date_to = date('Y-m-' . '01');
    $days = $this->dateInterval($date_to, $date_from);
    $percent = $sponsor ? $sponsor->porcent / $days : $data_plan->porcent / $days;

    if (date('Y-m', strtotime($plan->activated_at)) == date('Y-m')) {
      $dateInterval = $this->dateInterval(date('Y-m-d', strtotime($plan->activated_at)), date('Y-m-t'));
      $percentPeriodo = $dateInterval * $percent;
    } else {
      $percentPeriodo = $days * $percent;
    }

    $amount = ($plan->withdrawal_report == 0) ? $plan->amount : $plan->amount + $plan->income;
    $value = ($percentPeriodo / 100) * $amount;

    return $value;
  }
}
