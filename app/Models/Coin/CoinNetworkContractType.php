<?php

namespace App\Models\Coin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoinNetworkContractType extends Model
{
    use HasFactory;

    /**
     * table
     *
     * @var string
     */
    protected $table = 'coins_network_contract_type';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'coin_id',
        'name',
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
