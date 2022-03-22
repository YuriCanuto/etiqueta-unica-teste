<?php

namespace App\Http\Repositories;

use App\Models\Order\Order;

class OrderRepository {

    private $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * @param int $user_id
     * @param string $payment_method
     * @return Order
     */
    public function createOrder(int $user_id, string $payment_method): Order
    {
        $order = $this->order->create([
            'user_id'        => $user_id,
            'payment_method' => $payment_method,
        ]);

        return $order;
    }

}

