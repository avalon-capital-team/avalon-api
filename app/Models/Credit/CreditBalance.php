<?php

namespace App\Models\Credit;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Coin\Coin;
use App\Models\TokenSale\TokenSale;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;

class CreditBalance extends Model implements AuditableContract
{
    use HasFactory;
    use Auditable;

    /**
     * table
     *
     * @var string
     */
    protected $table = 'credits_balance';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'coin_id',
        'balance_enable',
        'balance_pending',
        'balance_canceled',
        'sales',
        'deposited',
        'used',
        'received',
        'withdrawal'
    ];

    /**
     * Get the wallet of Balance
     *
     * @return \App\Models\User
     */
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    /**
     * Get the Coin of balance
     *
     * @return \App\Models\Coin\Coin
     */
    public function coin()
    {
        return $this->hasOne(Coin::class, 'id', 'coin_id');
    }

    /**
     * Get converted to BRL
     *
     * @return float
     */
    public function convertToBrl(float $field)
    {
        return ($this->coin->price_brl * $field);
    }
}
