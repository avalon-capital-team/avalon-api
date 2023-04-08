<?php

namespace App\Models\Withdrawal;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;

class WithdrawalCrypto extends Model implements AuditableContract
{
    use HasFactory;
    use Auditable;

    /**
     * table
     *
     * @var string
     */
    protected $table = 'withdrawals_crypto';

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
        'destination',
        'amount',
        'fee',
        'paid_at',
        'hash'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'paid_at' => 'datetime'
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
