<?php

namespace App\Models\User;

use App\Models\User;
use App\Models\Coin\Coin;
use App\Models\Data\DataPlan;
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
        'coin_id',
        'amount',
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
        return $this->hasOne(Coin::class, 'id', 'coin_id');
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
