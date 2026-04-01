<?php

namespace Modules\Api\Services;

use Exception;
use Illuminate\Support\Facades\DB;
use Modules\Api\Models\Batch;
use Modules\Api\Models\BatchItem;
use Modules\Api\Models\ProviderRefund;

class PurchaseService
{
    /**
     * @param int $providerId
     * @param int $storageId
     * @param array $products Array of arrays: [['id' => 1, 'qty' => 10, 'price' => 100.00]]
     * @return Batch
     * @throws Exception
     */
    public function purchase(int $providerId, int $storageId, array $products): Batch
    {
        return DB::transaction(function () use ($providerId, $storageId, $products) {
            $batch = Batch::create(['provider_id' => $providerId]);

            foreach ($products as $product) {
                BatchItem::create([
                    'batch_id' => $batch->id,
                    'product_id' => $product['id'],
                    'storage_id' => $storageId,
                    'qty' => $product['qty'],
                    'available_qty' => $product['qty'],
                    'price' => $product['price']
                ]);
            }

            return $batch;
        });
    }

    /**
     * @param int $batchId
     * @param array $products Array of arrays: [['id' => 1, 'qty' => 5]]
     * @throws Exception
     */
    public function refundProvider(int $batchId, array $products): void
    {
        DB::transaction(function () use ($batchId, $products) {
            foreach ($products as $product) {
                $batchItem = BatchItem::where('batch_id', $batchId)
                    ->where('product_id', $product['id'])
                    ->first();

                if (!$batchItem) {
                    throw new Exception("Product ID {$product['id']} not found in Batch ID {$batchId}");
                }

                if ($batchItem->available_qty < $product['qty']) {
                    throw new Exception("Not enough available quantity for Product ID {$product['id']} to refund.");
                }

                $batchItem->available_qty -= $product['qty'];
                $batchItem->save();

                ProviderRefund::create([
                    'batch_item_id' => $batchItem->id,
                    'product_id' => $batchItem->product_id,
                    'qty' => $product['qty']
                ]);
            }
        });
    }
}
