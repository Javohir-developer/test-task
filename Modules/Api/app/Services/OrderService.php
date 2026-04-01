<?php

namespace Modules\Api\Services;

use Exception;
use Illuminate\Support\Facades\DB;
use Modules\Api\Models\Order;
use Modules\Api\Models\OrderItem;
use Modules\Api\Models\BatchItem;
use Modules\Api\Models\Product;

class OrderService
{
    /**
     * @param int $clientId
     * @param array $products Array of arrays: [['id' => 1, 'qty' => 5]]
     * @return Order
     * @throws Exception
     */
    public function createOrder(int $clientId, array $products): Order
    {
        return DB::transaction(function () use ($clientId, $products) {
            $order = Order::create(['client_id' => $clientId]);

            foreach ($products as $productRequest) {
                $productId = $productRequest['id'];
                $qtyNeeded = $productRequest['qty'];

                $product = Product::findOrFail($productId);
                
                // Get available batches sorted by oldest first (FIFO)
                $availableBatches = BatchItem::where('product_id', $productId)
                    ->where('available_qty', '>', 0)
                    ->orderBy('created_at', 'ASC')
                    ->lockForUpdate() // Prevent race conditions
                    ->get();

                $totalAvailable = $availableBatches->sum('available_qty');

                if ($totalAvailable < $qtyNeeded) {
                    throw new Exception("Insufficient stock for Product ID {$productId}. Available: {$totalAvailable}, Requested: {$qtyNeeded}");
                }

                foreach ($availableBatches as $batchItem) {
                    if ($qtyNeeded <= 0) break;

                    $take = min($qtyNeeded, $batchItem->available_qty);
                    
                    $batchItem->available_qty -= $take;
                    $batchItem->save();

                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $productId,
                        'batch_item_id' => $batchItem->id,
                        'qty' => $take,
                        'price' => $product->price // Snapshot of current selling price
                    ]);

                    $qtyNeeded -= $take;
                }
            }

            return $order;
        });
    }
}
