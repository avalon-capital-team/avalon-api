<?php

namespace Database\Seeders\Coin;

use App\Models\Coin\Coin;
use App\Models\Coin\CoinNetwork;
use App\Models\Coin\CoinNetworkContractType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CoinsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (!Coin::where('symbol', 'BRL')->first()) {
            Coin::create([
                'name' => 'Real',
                'symbol' => 'BRL',
                'type' => 'fiat',
                'decimals' => 2,
                'price_brl' => 1,
                'show_wallet' => true
            ]);

            DB::table('currencies')->insert([
                'name' => 'Real',
                'code' => 'BRL',
                'symbol' => 'R$',
                'format' => 'R$1.0,00',
                'exchange_rate' => '0',
                'active' => true,
            ]);
        }
        // if (!Coin::where('symbol', 'USD')->first()) {
        //     Coin::create([
        //         'name' => 'Dólar',
        //         'symbol' => 'USD',
        //         'type' => 'fiat',
        //         'decimals' => 2,
        //         'price_usd' => 1,
        //         'show_wallet' => true
        //     ]);

        //     DB::table('currencies')->insert([
        //         'name' => 'Dólar',
        //         'code' => 'USD',
        //         'symbol' => '$',
        //         'format' => '$1,0.00',
        //         'exchange_rate' => '0',
        //         'active' => true,
        //     ]);
        // }

        if (!Coin::where('symbol', 'BTC')->first()) {
            Coin::create([
                'name' => 'Bitcoin',
                'symbol' => 'BTC',
                'type' => 'coin',
                'explorer_address' => 'https://www.blockchain.com/btc-address/',
                'explorer_tx' => 'https://www.blockchain.com/btc-tx/',
                'decimals' => 6,
                'price_brl' => 140000,
                'show_wallet' => false,
                'chain_api' => 'bitcoin',
            ]);

            DB::table('currencies')->insert([
                'name' => 'Bitcoin',
                'code' => 'BTC',
                'symbol' => 'BTC',
                'format' => '1!0.000000 BTC',
                'exchange_rate' => '0',
                'active' => true,
            ]);
        }

        if (!Coin::where('symbol', 'USDT')->first()) {
            $coinEth = Coin::create([
                'name' => 'Tether',
                'symbol' => 'USDT',
                'type' => 'coin',
                'explorer_address' => 'https://etherscan.io/address/',
                'explorer_tx' => 'https://etherscan.io/tx/',
                'explorer_token' => 'https://etherscan.io/token/',
                'decimals' => 6,
                'chain_api' => 'ethereum',
                'price_brl' => 15000,
                'show_wallet' => false,
            ]);

            DB::table('currencies')->insert([
                'name' => 'Tether',
                'code' => 'USDT',
                'symbol' => 'USDT',
                'format' => '1!0.000000 USDT',
                'exchange_rate' => '0',
                'active' => true,
            ]);
        }
    }
}
