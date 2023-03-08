<?php

namespace App\Models\Coin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Jobs\Credit\CreateCreditBalance;
use Carbon\Carbon;

class Coin extends Model
{
    use HasFactory;
    /**
     * table
     *
     * @var string
     */
    protected $table = 'coins';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'symbol',
        'type',
        'price_brl',
        'price_usd',
        'price_eur',
        'explorer_tx',
        'explorer_address',
        'explorer_token',
        'volume_24h',
        'volume_change_24h',
        'show_wallet',
        'decimals',
        'chain_api',
        'name',
        'token_based'
    ];
    /**
     * boot of mode
     */
    public static function boot()
    {
        parent::boot();
        self::created(function ($model) {
            CreateCreditBalance::dispatch()->delay(Carbon::now()->addSeconds(rand(10, 20)));
        });
    }

    /**
     * Get Networks of Coin
     *
     * @return \App\Models\Coin\CoinNetwork
     */
    public function coinNetwork()
    {
        return $this->hasMany(CoinNetwork::class, 'coin_id', 'id');
    }

    /**
     * Get price based on default currency
     *
     * @return float
     */
    public function getPrice()
    {
        $default_currency = $this->price_brl;
        if (auth()->user()) {
            $default_currency = "price_" . strtolower(auth()->user()->default_currency);
            $default_currency = $this->{$default_currency};
        }

        return $default_currency;
    }
}
