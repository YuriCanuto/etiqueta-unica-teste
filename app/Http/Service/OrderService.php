<?php

namespace App\Http\Service;

use App\Http\Repositories\OrderRepository;
use App\Models\Order\Order;
use App\Models\User\User;

class OrderService
{
    private $orderRepository;

    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    /**
     * @param User $user
     * @param array $data
     * @return Order
     */
    public function createOrder(User $user, array $data): Order
    {
        $payment_method = data_get($data, 'payment_method');

        $order = $this->orderRepository->createOrder($user->id, $payment_method);

        return $order;
    }
}
