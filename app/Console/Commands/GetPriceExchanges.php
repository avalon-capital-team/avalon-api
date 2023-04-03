<?php

namespace App\Console\Commands;

use App\Http\Resources\Coin\CoinTrackerResource;
use Illuminate\Console\Command;

class GetPriceExchanges extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:getPriceExchanges';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'get p rice exchanges by coinmarketcap';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        (new CoinTrackerResource())->coinTrackingList();
    }
}
