<?php

namespace Database\Seeders\Order;

use App\Models\Order\OrderStatus;
use Illuminate\Database\Seeder;

class OrderStatusTableSeeder extends Seeder
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
                'name'  => 'Processando',
                'color' => 'info',
                'icon'  => '/assets/icons/orders/processing.png'
            ),
            array(
                'id'    => 3,
                'name'  => 'Expirado',
                'color' => 'danger',
                'icon'  => '/assets/icons/orders/expired.png'
            ),
            array(
                'id'    => 4,
                'name'  => 'Cancelado',
                'color' => 'danger',
                'icon'  => '/assets/icons/orders/cancelled.png'
            ),
            array(
                'id'    => 5,
                'name'  => 'Cancelado pelo Operador',
                'color' => 'danger',
                'icon'  => '/assets/icons/orders/cancelled.png'
            ),
            array(
                'id'    => 6,
                'name'  => 'Aprovado',
                'color' => 'success',
                'icon'  => '/assets/icons/orders/approved.png'
            ),
        );

        OrderStatus::insert($order_status);
    }
}
