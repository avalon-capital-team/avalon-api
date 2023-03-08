<?php

namespace App\Models\Coin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoinNetwork extends Model
{
    use HasFactory;
    /**
     * table
     *
     * @var string
     */
    protected $table = 'coins_network';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'coin_id',
        'contract',
        'status',
        'contract_type_id',
        'blockchain_id'
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

    /**
     * Get Type of Network
     *
     * @return \App\Models\Coin\CoinNetworkContractType
     */
    public function type()
    {
        return $this->belongsTo(CoinNetworkContractType::class, 'contract_type_id');
    }

    /**
     * Get Blockchain of Coin
     *
     * @return \App\Models\Coin\Coin
     */
    public function blockchain()
    {
        return $this->belongsTo(Coin::class, 'blockchain_id');
    }
}
