<?php

namespace App\Models\User;

use App\Models\Data\DataPlan;
use App\Models\User;
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

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'plan_id',
        'amount',
        'acting',
        'paymente_voucher_url',
    ];

    /**
     * createPlan
     *
     * @param  App\Models\User $user
     * @param  int $coin_id
     * @param  int $payment_method
     * @return \App\Models\User\UserPlan || boolean;
     */
    public function createOrder(User $user)
    {
        dd($plan);
        $plan = new UserPlan();
        $plan->user_id             = $user->id;
        $plan->plan_id             = $plan_id;
        $plan->acting              = false;
        $plan->amount              = $amount;


        if ($plan->save()) {
            return $plan;
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
    public function plan()
    {
        return $this->hasOne(DataPlan::class, 'id', 'plan_id');
    }
}
