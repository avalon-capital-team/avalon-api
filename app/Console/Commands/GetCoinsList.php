<?php

namespace App\Console\Commands;

use App\Http\Resources\Coin\CoinResource;
use App\Jobs\Credit\CreateCreditBalance;
use Illuminate\Console\Command;
use Carbon\Carbon;

class GetCoinsList extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:getCoinsList';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get a coins and informations';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        CreateCreditBalance::dispatch()->delay(Carbon::now()->addSeconds(rand(10, 20)));
        (new CoinResource())->coinData();
    }
}
