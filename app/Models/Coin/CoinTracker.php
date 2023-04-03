<?php

namespace App\Models\Coin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoinTracker extends Model
{
    use HasFactory;
    /**
     * table
     *
     * @var string
     */
    protected $table = 'coins_tracker';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'coin_id',
        'name',
        'price_usd'
    ];

    /**
     * Get coin of Network
     *
     * @return \App\Models\Coin\Coin
     */
    public function coin()
    {
        return $this->belongsTo(Coin::class, 'coin_id');
    }
}
