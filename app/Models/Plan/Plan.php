<?php

namespace App\Models\Plan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;
use App\Models\User;
use App\Models\User\UserPlan;
use App\Models\Coin\Coin;
use App\Models\Data\DataPlan;
use App\Nova\Models\System\PaymentMethod;

class Plan extends Model implements AuditableContract
{
    use HasFactory;
    use Auditable;

    /**
     * table
     *
     * @var string
     */
    protected $table = 'plans';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'user_plan_id',
        'plan_id',
        'coin_id',
        'amount',
        'income',
        'acting',
        'withdrawal_report',
        'payment_voucher_url',
        'payment_method_id',
    ];

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            do {
                $token = substr(md5(uniqid(1, true)), 0, 8);
            } while (self::where('token', $token)->exists());

            $model->token = $token;
        });
    }

    /**
     * createOrder
     *
     * @param  App\Models\User $user
     * @param  int $user_plan_id
     * @param  int $plan_id
     * @param  int $coin_id
     * @param  float $amount
     * @param  bool $withdrawal_report
     * @param  int $payment_method
     * @return \App\Models\Plan\Plan;
     */
    public function createPlan(User $user, int $user_plan_id, int $plan_id, int $coin_id, float $amount, bool $withdrawal_report = false, int $payment_method)
    {
        return Plan::create([
            'user_id' => $user->id,
            'user_plan_id' => $user_plan_id,
            'plan_id' => $plan_id,
            'coin_id' => $coin_id,
            'amount' => $amount,
            'income' => 0.000000,
            'acting' => 0,
            'withdrawal_report' => $withdrawal_report,
            'payment_method_id' => $payment_method
        ]);
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
    public function payment_method()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
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
     * Get the plan detains
     *
     * @return \App\Models\User\UserPlan
     */
    public function userPlan()
    {
        return $this->belongsTo(UserPlan::class, 'user_plan_id');
    }

    /**
     * Get the plan detains
     *
     * @return \App\Models\User\UserPlan
     */
    public function dataUser()
    {
      $plans = Plan::get()->limit(10);
      foreach ($plans as $plan){
        $plan->user;
        $plan->dataPlan;
        $plan->coin;
      }
        return $plans;
    }
}
