<?php

namespace App\Models\Credit;

use App\Helpers\UuidHelper;

use App\Http\Resources\Credit\CreditBalanceResource;
use App\Models\Coin\Coin;
use App\Models\Data\DataPlan;
use App\Models\Order\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;

class Credit extends Model implements AuditableContract
{
    use HasFactory;
    use Auditable;

    /**
     * table
     *
     * @var string
     */
    protected $table = 'credits';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'uuid',
        'user_id',
        'coin_id',
        'amount',
        'base_amount',
        'description',
        'type_id',
        'status_id',
        'plan_id'
    ];

    /**
     * boot of mode
     */
    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->uuid = UuidHelper::generate($model);
        });
        self::created(function ($model) {
            (new CreditBalanceResource())->createOrUpdateBalance($model->user, $model->coin_id, floatval($model->amount), $model->plan_id);
        });
    }

    /**
     * @param $query
     * @param array $array
     * @return mixed
     */
    public function scopeFilterSearch($query, $filters)
    {
        if (isset($filters['uuid']) && $filters['uuid']) {
            $query->where('uuid', 'LIKE', '%' . $filters['uuid'] . '%');
        }

        if (isset($filters['type_id']) && $filters['type_id']) {
            $query->where('type_id', 'LIKE', '%' . $filters['type_id'] . '%');
        }

        if (isset($filters['description']) && $filters['description']) {
            $query->where('description', 'LIKE', '%' . $filters['description'] . '%');
        }

        if (isset($filters['coin_id']) && $filters['coin_id'] && $filters['coin_id'] != 'all') {
            $query->where('coin_id', $filters['coin_id']);
        }
        if (isset($filters['date_from']) && $filters['date_from']) {
            $query->where('created_at', '>=', Carbon::parse($filters['date_from'])->format('Y-m-d H:i:s'));
        }

        if (isset($filters['date_to']) && $filters['date_to']) {
            $query->where('created_at', '<=', Carbon::parse($filters['date_to'])->format('Y-m-d') . ' 23:59:59');
        }

        return $query;
    }

    /**
     * Get the User
     *
     * @return User
     */
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    /**
     * Get the transfer User
     *
     * @return User
     */
    public function transferUser()
    {
        return $this->hasOne(User::class, 'id', 'transfer_user_id');
    }

    /**
     * Get Coin of Credit
     *
     * @return \App\Models\Coin\Coin
     */
    public function coin()
    {
        return $this->hasOne(Coin::class, 'id', 'coin_id');
    }

    /**
     * Get Status of Credit
     *
     * @return \App\Models\Credit\CreditStatus
     */
    public function status()
    {
        return $this->hasOne(CreditStatus::class, 'id', 'status_id');
    }

    /**
     * Get Type of Credit
     *
     * @return \App\Models\Credit\CreditType
     */
    public function type()
    {
        return $this->hasOne(CreditType::class, 'id', 'type_id');
    }
}
