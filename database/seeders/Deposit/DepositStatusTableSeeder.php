<?php

namespace Database\Seeders\Deposit;

use App\Models\Deposit\DepositStatus;
use Illuminate\Database\Seeder;

class DepositStatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $order_status = array(
            array(
                'id'    => 1,
                'name'  => 'Aguardando Pagamento',
                'color' => 'warning',
                'icon'  => '/assets/icons/orders/pending.png'
            ),
            array(
                'id'    => 2,
                'name'  => 'Comprovante enviado',
                'color' => 'info',
                'icon'  => '/assets/icons/orders/processing.png'
            ),
            array(
                'id'    => 3,
                'name'  => 'Rejeitado',
                'color' => 'danger',
                'icon'  => '/assets/icons/orders/cancelled.png'
            ),
            array(
                'id'    => 4,
                'name'  => 'Aprovado',
                'color' => 'success',
                'icon'  => '/assets/icons/orders/approved.png'
            ),
        );

        DepositStatus::insert($order_status);
    }
}
