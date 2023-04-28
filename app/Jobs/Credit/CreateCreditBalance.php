<?php

namespace App\Jobs\Credit;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Coin\Coin;
use App\Models\User;

class CreateCreditBalance implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;


    /**
     * Create a new job instance.
     *
     * @param \App\Models\Coin\Coin
     * @return void
     */
    public function __construct()
    {
    }
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $users = User::get();
        $coins = Coin::get();
        foreach ($users as $user) {
            foreach ($coins as $coin) {
                if ($coin->show_wallet == true) {
                    if (!$user->creditBalance()->where('coin_id', $coin->id)->first()) {
                        $user->creditBalance()->create([
                            'coin_id' => $coin->id
                        ]);
                    }
                }
            }
        }
    }
}
