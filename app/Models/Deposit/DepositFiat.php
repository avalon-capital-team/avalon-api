<?php

namespace App\Models\Deposit;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Coin\Coin;
use App\Models\User;
use App\Models\System\PaymentMethod\PaymentMethod;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;

class DepositFiat extends Model implements AuditableContract
{
    use HasFactory;
    use Auditable;

    /**
     * table
     *
     * @var string
     */
    protected $table = 'deposits_fiat';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'token',
        'user_id',
        'coin_id',
        'payment_method_id',
        'amount',
        'status_id',
        'provider_id',
        'approved_by',
        'receipt_file',
        'message',

    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'provider_data' => 'array',
    ];

    /**
     * Boot of model
     */
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
     * Get the Coin
     *
     * @return \App\Models\Coin\Coin
     */
    public function coin()
    {
        return $this->hasOne(Coin::class, 'id', 'coin_id');
    }

    /**
     * Get the User
     *
     * @return \App\Models\User
     */
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    /**
     * Get status of deposit
     *
     * @return \App\Models\Deposit\DepositStatus
     */
    public function status()
    {
        return $this->hasOne(DepositStatus::class, 'id', 'status_id');
    }

    /**
     * Get payment method of deposit
     *
     * @return \App\Models\System\PaymentMethod\PaymentMethod
     */
    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }
}
