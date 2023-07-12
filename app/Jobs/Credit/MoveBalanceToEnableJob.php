<?php

namespace App\Jobs\Credit;

use App\Models\Credit\CreditBalance;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class MoveBalanceToEnableJob implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  protected $creditBalance;
  protected $amount;
  /**
   * Create a new job instance.
   *
   * @return void
   */
  public function __construct(CreditBalance $creditBalance, float $amount)
  {
    $this->creditBalance = $creditBalance;
    $this->amount = $amount;
  }

  /**
   * Execute the job.
   *
   * @return void
   */
  public function handle()
  {
    if ($this->creditBalance->balance_pending >= $this->amount) {
      DB::transaction(function () {
        $this->creditBalance->balance_pending -= $this->amount;
        $this->creditBalance->balance_enable += $this->amount;
        $this->creditBalance->save();
      });
    }
  }
}
