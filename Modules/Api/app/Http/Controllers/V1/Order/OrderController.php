<?php

namespace Modules\Api\app\Http\Controllers\V1\Order;

use Modules\Api\Http\Controllers\BaseApiController;
use Modules\Api\app\Http\Requests\V1\Order\CreateOrderRequest;
use Modules\Api\app\Services\OrderService;

class OrderController extends BaseApiController
{
    public function __construct(private OrderService $orderService) {}

    public function createOrder(CreateOrderRequest $request)
    {
        try {
            $order = $this->orderService->createOrder(
                $request->input('client_id'),
                $request->input('products')
            );

            return $this->sendResponse([
                'message' => 'Client order created successfully.',
                'order_id' => $order->id
            ]);
        } catch (\Exception $e) {
            return $this->sendError([$e->getMessage()], 400);
        }
    }
}
