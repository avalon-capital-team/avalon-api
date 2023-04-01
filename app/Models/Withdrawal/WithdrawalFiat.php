<?php

namespace App\Models\Withdrawal;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Coin\Coin;
use App\Models\Credit\Credit;
use App\Models\User;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;

class WithdrawalFiat extends Model implements AuditableContract
{
    use HasFactory;
    use Auditable;

    /**
     * table
     *
     * @var string
     */
    protected $table = 'withdrawals_fiat';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'coin_id',
        'user_id',
        'debit_id',
        'status_id',
        'type',
        'reject_motive',
        'amount',
        'payment_confirmation'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'data' => 'array'
    ];

    /**
     * Cancel withdrawal
     *
     * @return bool
     * @throws \Exception
     */
    public function cancel()
    {
        return false;
    }

    /**
     * User of Withdrawal
     *
     * @return \App\Models\User
     */
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    /**
     * Status of Withdrawal
     *
     * @return \App\Models\Withdrawal\WithdrawalStatus
     */
    public function status()
    {
        return $this->hasOne(WithdrawalStatus::class, 'id', 'status_id');
    }

    /**
     * Coin of Withdrawal
     *
     * @return \App\Models\Coin\Coin
     */
    public function coin()
    {
        return $this->hasOne(Coin::class, 'id', 'coin_id');
    }

    /**
     * Debit of Withdrawal
     *
     * @return \App\Models\Credit\Credit
     */
    public function debit()
    {
        return $this->hasOne(Credit::class, 'id', 'debit_id');
    }
}
