<?php

namespace App\Http\Service;

use App\Http\Repositories\OrderItemRepository;
use App\Models\Order\Order;

class OrderItensService {

    private $orderItemRepository;

    public function __construct(OrderItemRepository $orderItemRepository)
    {
        $this->orderItemRepository = $orderItemRepository;
    }

    /**
     * @param Order $order
     * @param array $data
     * @return void
     */
    public function createOrderItens(Order $order, array $data): void
    {
        $coupon   = data_get($data, 'coupon');
        $products = data_get($data, 'products');

        foreach($products as $product) {
            $data['product_id'] = data_get($product, '0.id');
            $data['amount']     = data_get($product, '0.amount');
            $data['coupon']     = $coupon;

            $this->orderItemRepository->createOrderItens($order, $data);
        }
    }
}
