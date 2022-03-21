<?php

namespace App\Http\Service;

use App\Models\Product\Product;

class ProductService {

    public function transform(Product $product, int $quantity = 0)
    {
        return [
            'id'       => $product->id,
            'name'     => $product->name,
            'amount'   => $product->amount,
            'quantity' => $quantity
        ];
    }
}
