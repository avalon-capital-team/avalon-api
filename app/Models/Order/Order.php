<?php

namespace App\Models\Order;

use App\Http\Resources\Order\OrderResource;
use App\Models\Coin\Coin;
use App\Models\Credit\Credit;
use App\Models\System\PaymentMethod\PaymentMethod;
use App\Models\TokenSale\TokenSale;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Models\User;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;

class Order extends Model implements AuditableContract
{
    use HasFactory;
    use Auditable;

    /**
     * table
     *
     * @var string
     */
    protected $table = 'orders';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'coin_id',
        'payment_method_id',
        'total',
        'fee',
        'status_id',
        'paid_at',
        'manual_by',
        'approve_type'
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
     * boot of mode
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
     * @param $query
     * @param $request
     * @return mixed
     */
    public function scopeFilterSearch($query, $request)
    {
        if ($request->date_to) {
            $query->where('created_at', '<', Carbon::parse($request->date_to)->format('Y-m-d H:i:s'));
        }

        if ($request->date_from) {
            $query->where('created_at', '>', Carbon::parse($request->date_from)->format('Y-m-d H:i:s'));
        }

        if ($request->payment_method_id) {
            $query->where('payment_method_id', $request->payment_method_id);
        }

        if ($request->status_id) {
            $query->where('status_id', $request->status_id);
        }

        if ($request->paid_at) {
            $query->where('paid_at', 'LIKE', '%' . Carbon::parse($request->paid_at)->format('Y-m-d H:i:s') . '%');
        }

        return $query;
    }

    /**
     * createOrder
     *
     * @param  App\Models\User $user
     * @param  int $coin_id
     * @param  int $payment_method
     * @return \App\Models\Order\Order || boolean;
     */
    public function createOrder(User $user, $data)
    {
        dd($data);

        $total = '10';

        $order = new Order();
        $order->user_id             = $user->id;
        $order->payment_method_id   = $data['payment_method'];
        $order->status_id           = 1;
        $order->coin_id             = $data['coin_id'];
        $order->fee                 = 0;
        $order->total               = $total;

        if ($order->save()) {
            return $order;
        }

        return false;
    }

    /**
     * updateHistoryStatus
     *
     * @param  int $status_id
     * @param  string $comment
     * @param  bool $notify
     * @return boolean
     */
    public function updateHistoryStatus($status_id = 1, $comment = '', $notify = 0)
    {
        $this->orderHistory()->create([
            'order_id'      => $this->id,
            'status_id'     => $status_id,
            'notify'        => $notify,
            'comment'       => $comment,
            'created_at'    => date("Y-m-d H:i:s")
        ]);

        $this->status_id = $status_id;

        if ($status_id == 6 && !$this->paid_at) {
            $this->paid_at = date("Y-m-d H:i:s");

            $fee = $this->orderTotal()->where('code', 'fee')->first();
            if ($fee) {
                // $orderType = ($this->orderDeposit) ? 'deposit' : 'buy';
                // $description = 'Taxa do Pedido #'.$this->id;
                // (new FeeHistoryResource())->create($this->coin, $this->user, $orderType, 'Order', $this->id, $fee->value, $description);
            }
        }

        return $this->save();
    }

    /**
     * Update order approve type
     *
     * @param  string $type
     */
    public function updateApproveType(string $type)
    {
        $this->approve_type = $type;
        $this->save();
    }

    // /**
    //  * executeWhenOrderIsPaid
    //  */
    // public function executeWhenOrderIsPaid()
    // {
    //     if ($this->orderTokenSale) {
    //         return (new OrderResource())->approveOrderTokenSale($this);
    //     }
    // }

    /**
     * Get the user.
     *
     * @return User
     */
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }


    /**
     * Get PaymentMethod of Order
     *
     * @return \App\Models\System\PaymentMethod\PaymentMethod
     */
    public function paymentMethod()
    {
        return $this->hasOne(PaymentMethod::class, 'id', 'payment_method_id');
    }

    /**
     * Get status of Order
     *
     * @return \App\Models\Order\OrderStatus
     */
    public function status()
    {
        return $this->hasOne(OrderStatus::class, 'id', 'status_id');
    }

    /**
     * Get credits of Order
     *
     * @return \App\Models\Credit\Credit
     */
    public function credits()
    {
        return $this->hasMany(Credit::class, 'order_id', 'id');
    }

    /**
     * Get payment of Order
     *
     * @return \App\Models\Order\OrderPayment
     */
    public function payment()
    {
        return $this->hasOne(OrderPayment::class, 'order_id', 'id');
    }

    /**
     * Get Coin of Order
     *
     * @return \App\Models\Coin\Coin
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
