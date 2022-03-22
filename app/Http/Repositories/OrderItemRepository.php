<?php

namespace App\Http\Repositories;

use App\Models\Order\Order;
use App\Models\Order\OrderItens;

class OrderItemRepository {

    private $orderItens;

    private $order;

    public function __construct(Order $order, OrderItens $orderItens)
    {
        $this->order      = $order;
        $this->orderItens = $orderItens;
    }

    /**
     * @param Order $order
     * @param mixed $data
     * @return void
     */
    public function createOrderItens(Order $order, $data): void
    {
        $order->itens()->create([
                    'product_id' => data_get($data, 'product_id'),
                    'amount' => data_get($data, 'amount'),
                    'coupon' => data_get($data, 'coupon', 0),
                ]);
    }
}
