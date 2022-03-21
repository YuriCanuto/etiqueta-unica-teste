<?php

namespace App\Http\Controllers\Orders;

use App\Http\Controllers\Controller;
use App\Models\Order\Order;
use App\Models\Order\OrderItens;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function checkout(Request $request)
    {
        DB::beginTransaction();


        try {

            $data = $request->all();

            $user_id = $request->user()->id;

            $payment_method = data_get($data, 'payment_method');
            $coupon = data_get($data, 'coupon');
            $products = data_get($data, 'products');

            $order = Order::create([
                'user_id'        => $user_id,
                'payment_method' => $payment_method,
            ]);

            foreach($products as $product) {
                $order->itens()->create([
                    'product_id' => data_get($product, '0.id'),
                    'amount' => data_get($product, '0.amount'),
                    'coupon' => $coupon,
                ]);
            }

            DB::commit();

        } catch (\Exception $e) {
            //throw $th;
            dd($e);

            DB::rollBack();
        }
    }
}
