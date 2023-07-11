<?php

namespace App\Models\User;

use App\Models\User;
use App\Models\Coin\Coin;
use App\Models\Data\DataPlan;
use App\Models\Plan\Plan;
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
    if ($this->withdrawal_report == 0) {
      return $this->amount * 0.05;
    }
    return ($this->amount + $this->income) * 0.05;
}

  /**
   * Get user
   *
   * @return App\Models\User
   */
  public function getTotalAttribute()
  {
    if ($this->withdrawal_report == 0) {
      return $this->amount;
    }
    return $this->amount + $this->income;
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
