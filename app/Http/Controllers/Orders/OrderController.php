<?php

namespace App\Http\Controllers\Orders;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Http\Service\OrderItensService;
use App\Http\Service\OrderService;
use App\Models\Order\Order;
use App\Models\Order\OrderItens;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    private $orderService;

    private $orderItensService;

    public function __construct(OrderService $orderService, OrderItensService $orderItensService)
    {
        $this->orderService      = $orderService;
        $this->orderItensService = $orderItensService;
    }

    public function checkout(Request $request)
    {
        DB::beginTransaction();

        try {

            $data = $request->all();
            $user = $request->user();

            $order = $this->orderService->createOrder($user, $data);

            $this->orderItensService->createOrderItens($order, $data);

            DB::commit();

            $user->sendNewOrderNotification($order);

            return new OrderResource($order);

        } catch (\Exception $e) {
            DB::rollBack();

            dd($e);

            return response([
                'message' => 'Sorry! something went wrong'
            ], JsonResponse::HTTP_NOT_FOUND);
        }
    }
}
