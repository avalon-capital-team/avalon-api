<?php

namespace App\Models\User;

use App\Models\User;
use App\Models\Coin\Coin;
use App\Models\Credit\CreditBalance;
use App\Models\Data\DataPlan;
use App\Models\Plan\Plan;
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
    $balance = CreditBalance::where('user_id', $this->user_id)->where('coin_id', $this->coin_id)->first();
    dd('1',$balance);
    $total = $balance->balance_placed * 0.05;
    return $total;
  }

  /**
   * Get user
   *
   * @return App\Models\User
   */
  public function getTotalAttribute()
  {
    $balance = CreditBalance::where('user_id', $this->user_id)->where('coin_id', $this->coin_id)->first();
    dd('2',$balance);
    return $balance->balance_placed;
  }

  /**
   * Get user
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
}
