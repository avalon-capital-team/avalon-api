<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Database\Seeders\Data\DataCountrySeeder;
use Database\Seeders\Data\DataGenreSeeder;
use Database\Seeders\Data\DataPlanSeeder;
use Database\Seeders\Data\DataOngSeeder;
use Database\Seeders\Data\DataNotificationChannelSeeder;
use Database\Seeders\Data\DataNotificationTypeSeeder;
use Database\Seeders\Data\DataPrivacyTypeOptionSeeder;
use Database\Seeders\Data\DataPrivacyTypeSeeder;
use Database\Seeders\Onboarding\OnboardingStepSeeder;
use Database\Seeders\User\UserComplianceStatusSeeder;
use Database\Seeders\User\UserSeeder;
use Database\Seeders\User\UserStatusSeeder;
use Database\Seeders\Coin\CoinsTableSeeder;
use Database\Seeders\Credit\CreditTypeTableSeeder;
use Database\Seeders\Credit\CreditStatusTableSeeder;
use Database\Seeders\Order\OrderStatusTableSeeder;
use Database\Seeders\System\PaymentMethodTableSeeder;
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

        # Payment Method
        $this->call(PaymentMethodTableSeeder::class);

        # Order
        $this->call(OrderStatusTableSeeder::class);

        # Data
        $this->call(DataGenreSeeder::class);
        $this->call(DataNotificationChannelSeeder::class);
        $this->call(DataNotificationTypeSeeder::class);
        $this->call(DataCountrySeeder::class);
        $this->call(DataPrivacyTypeOptionSeeder::class);
        $this->call(DataPlanSeeder::class);
        $this->call(DataOngSeeder::class);
        $this->call(DataPrivacyTypeSeeder::class);

        # Onboarding
        $this->call(OnboardingStepSeeder::class);

        # User
        $this->call(UserComplianceStatusSeeder::class);
        $this->call(UserStatusSeeder::class);
        $this->call(UserSeeder::class);
    }
}
