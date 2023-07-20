<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Database\Seeders\Data\DataCountrySeeder;
use Database\Seeders\Data\DataGenreSeeder;
use Database\Seeders\Data\DataPlanSeeder;
use Database\Seeders\Data\DataOngSeeder;
use Database\Seeders\Data\DataBankSeeder;
use Database\Seeders\Data\DataPercentSeeder;
use Database\Seeders\Data\DataNotificationChannelSeeder;
use Database\Seeders\Data\DataNotificationTypeSeeder;
use Database\Seeders\Data\DataPrivacyTypeOptionSeeder;
use Database\Seeders\Data\DataPrivacyTypeSeeder;
use Database\Seeders\Onboarding\OnboardingStepSeeder;
use Database\Seeders\User\UserComplianceStatusSeeder;
use Database\Seeders\User\UserSeeder;
use Database\Seeders\User\UserStatusSeeder;
use Database\Seeders\Deposit\DepositStatusTableSeeder;
use Database\Seeders\Coin\CoinsTableSeeder;
use Database\Seeders\Credit\CreditTypeTableSeeder;
use Database\Seeders\Credit\CreditStatusTableSeeder;
use Database\Seeders\Order\OrderStatusTableSeeder;
use Database\Seeders\System\PaymentMethodTableSeeder;
use Database\Seeders\System\LimitOfUserTableSeeder;
use Database\Seeders\Withdrawal\WithdrawalStatusTableSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        # Coin
        $this->call(CoinsTableSeeder::class);

        # Credit
        $this->call(CreditTypeTableSeeder::class);
        $this->call(CreditStatusTableSeeder::class);

        # System
        $this->call(PaymentMethodTableSeeder::class);
        $this->call(LimitOfUserTableSeeder::class);

        # Order
        $this->call(OrderStatusTableSeeder::class);

        # Withdrawal
        $this->call(WithdrawalStatusTableSeeder::class);

        # Deposit
        $this->call(DepositStatusTableSeeder::class);

        # Data
        $this->call(DataGenreSeeder::class);
        $this->call(DataNotificationChannelSeeder::class);
        $this->call(DataNotificationTypeSeeder::class);
        $this->call(DataPrivacyTypeOptionSeeder::class);
        $this->call(DataPlanSeeder::class);
        $this->call(DataOngSeeder::class);
        $this->call(DataPrivacyTypeSeeder::class);
        $this->call(DataBankSeeder::class);
        // $this->call(DataCountrySeeder::class);
        $this->call(DataPercentSeeder::class);

        # Onboarding
        $this->call(OnboardingStepSeeder::class);

        # User
        $this->call(UserComplianceStatusSeeder::class);
        $this->call(UserStatusSeeder::class);
        $this->call(UserSeeder::class);
    }
}
