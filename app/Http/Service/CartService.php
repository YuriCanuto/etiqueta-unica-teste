<?php

namespace App\Http\Service;

use App\Models\Product\Product;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class CartService
{
    const SESSION_ID = 'cart';

    private $product;

    private $productService;

    public function __construct(Product $product, ProductService $productService)
    {
        session_start();

        $this->product = $product;
        $this->productService = $productService;
        $this->session_id = self::SESSION_ID;
    }

    /**
     * @param array $item
     * @return JsonResponse|void
     * @throws BindingResolutionException
     */
    public function addProducts(array $item)
    {
        try {
            $product = $this->product->findOrFail($item['id']);
        } catch (\Exception $e) {
            return response()->json([], JsonResponse::HTTP_NOT_FOUND);
        }

        $_SESSION['products'][$product->id] = [
            $this->productService->transform($product, $item['quantity'])
        ];
    }

    public function clearProducts(): void
    {
        unset($_SESSION['products']);
        unset($_SESSION['cupom']);
        unset($_SESSION['payment_method']);
        unset($_SESSION['total']);
    }

    public function getQuantity(): JsonResponse
    {
        $quantity = 0;

        $products = data_get($_SESSION, 'products', null);

        if(!$products) {
            return response()->json([], Response::HTTP_BAD_REQUEST);
        }

        foreach ($products as $product){
            $quantity += data_get($product, '0.quantity', 0);
        }

        return response()->json(['quantity' => $quantity]);
    }

    private function getTotalAmount(float $coupom = 0): float
    {
        $amount = 0;

        if(isset($_SESSION['products'])){
            foreach ($_SESSION['products'] as $product){
                $amount += $product[0]['amount'] * $product[0]['quantity'];
            }
        }

        return $amount - $coupom;
    }

    public function cart(array $data): JsonResponse
    {
        $coupon = data_get($data, 'coupon', 0);
        $_SESSION['coupon'] = $coupon;
        $_SESSION['payment_method'] = $data['payment_method'];
        $_SESSION['total'] = $this->getTotalAmount($coupon);

        return response()->json($_SESSION);
    }
}
