<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Resources\Plan\PlanResource;

class CheckPlanDaily extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:checkPlanDaily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check the plan for profitability.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        (new PlanResource())->checkIfNeedPayToday();
    }
}
