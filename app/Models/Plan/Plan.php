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
        'payment_voucher_url',
    ];

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
     * Get the plan detains
     *
     * @return \App\Models\User\UserPlan
     */
    public function userPlan()
    {
        return $this->belongsTo(UserPlan::class, 'user_plan_id');
    }
}
