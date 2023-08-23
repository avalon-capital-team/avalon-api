<?php

namespace App\Models\User;

use App\Models\User;
use App\Models\Coin\Coin;
use App\Models\Credit\CreditBalance;
use App\Models\Data\DataPlan;
use App\Models\Plan\Plan;
use DateTime;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPlan extends Model
{
  use HasFactory;
  /**
   * table
   *
   * @var string
   */
  protected $table = 'users_plan';
  protected $appends = ['total', 'total_month'];

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'user_id',
    'plan_id',
    'coin_id',
    'amount',
    'acting',
    'payment_voucher_url',
  ];

  public function getTotalMonthAttribute()
  {
    if ($this->acting == 1) {
      return $this->calculePercent($this);
    } else {
      return 0;
    }
  }

  public function getTotalAttribute()
  {
    $balance = CreditBalance::where('user_id', $this->user_id)->where('coin_id', $this->coin_id)->first();
    if ($balance) {
      $total = $balance->balance_placed;
      return $total;
    } else {
      return 0;
    };
  }

  /**
   * Get user plan
   *
   * @return App\Models\UserPlan
   */
  public function activatedAt($id)
  {
    $userPlan = UserPlan::find($id);
    if ($userPlan) {
      $userPlan->activated_at = Carbon::now();
      $userPlan->save();
      return true;
    }
    return false;
  }

  /**
   * Get user
   *
   * @return App\Models\User
   */
  public function user()
  {
    return $this->belongsTo(User::class, 'user_id');
  }

  /**
   * Get the plan detains
   *
   * @return \App\Models\Data\DataPlan
   */
  public function coin()
  {
    return $this->belongsTo(Coin::class, 'coin_id');
  }

  /**
   * Get the plan detains
   *
   * @return \App\Models\Data\DataPlan
   */
  public function dataPlan()
  {
    return $this->belongsTo(DataPlan::class, 'plan_id');
  }

  /**
   * Get the plans detains
   *
   * @return \App\Models\Data\DataPlan
   */
  public function plan()
  {
    return $this->hasMany(Plan::class, 'user_plan_id', 'id');
  }


  private function dateInterval($date_from, $date_to)
  {
    $date_from = new DateTime($date_from);
    $date_to = new DateTime($date_to);

    $dateInterval = $date_from->diff($date_to);
    return $dateInterval->days + 1;
  }

  private function calculePercent($plan)
  {
    $balance = CreditBalance::where('user_id', $this->user_id)->where('coin_id', $this->coin_id)->first();
    $data_plan = DataPlan::where('id', $plan->plan_id)->first();
    $date_from = date('Y-m-t');
    $date_to = date('Y-m-' . '01');
    $days = $this->dateInterval($date_to, $date_from);
    $percent = $data_plan->porcent / $days;
    $percentPeriodo = $days * $percent;

    if (date('Y-m', strtotime($plan->activated_at)) == date('Y-m')) {
      $aportes = Plan::where('user_id', $this->user_id)->where('activated_at', $plan->activated_at)->get();
      $valueAporte = 0;
      $valueTotal = 0;
      $valueMonthAporte = 0;

      foreach ($aportes as $aporte) {
        $dateInterval = $this->dateInterval(date('Y-m-d', strtotime($aporte->activated_at)), date('Y-m-t'));
        $percentPeriodoAporte = $dateInterval * $percent;
        $value = ($percentPeriodoAporte / 100) * $aporte->amount;
        $total = ($percentPeriodo / 100) * $aporte->amount;

        $valueTotal += $total;
        $valueAporte += $value;
        $valueMonthAporte += $aporte->amount;
      }
      if ($plan->amount === $balance->balance_placed) {
        $amount = ($plan->withdrawal_report == 0) ? $plan->amount : $plan->amount + $plan->income;
      } else {
        $amount = ($plan->withdrawal_report == 0) ? $balance->balance_placed : 0;
      }

      $value = ($percentPeriodo / 100) * $amount;
      $value = $value - $valueTotal + $valueAporte;
      return $value;
    }

    if ($plan->amount === $balance->balance_placed) {
      $amount = ($plan->withdrawal_report == 0) ? $plan->amount : $plan->amount + $plan->income;
    } else {
      $amount = ($plan->withdrawal_report == 0) ? $balance->balance_placed : $plan->amount + $plan->income;
    }

    $value = ($percentPeriodo / 100) * $amount;

    return $value;
  }
}
