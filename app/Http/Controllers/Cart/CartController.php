<?php

namespace App\Http\Controllers\Cart;

use App\Http\Controllers\Controller;
use App\Http\Service\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    private $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function addProducts(Request $request) {

        return $this->cartService->addProducts($request->all());
    }

    public function clearProducts()
    {
        return $this->cartService->clearProducts();
    }

    public function getQuantity()
    {
        return $this->cartService->getQuantity();
    }

    public function getCart(Request $request)
    {
        $data = $request->only(['payment_method', 'coupom']);

        return $this->cartService->cart($data);
    }
}
