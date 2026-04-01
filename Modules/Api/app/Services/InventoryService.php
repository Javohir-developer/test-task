<?php

namespace Modules\Api\Services;

use Illuminate\Support\Facades\DB;
use Modules\Api\Models\Product;

class InventoryService
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function getAvailableProducts()
    {
        // Fetch products along with their category name and sum up available_qty from batch_items
        return DB::table('products')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->leftJoin('batch_items', 'products.id', '=', 'batch_items.product_id')
            ->select(
                'products.id',
                'products.name',
                'categories.name as category_name',
                'products.price',
                DB::raw('COALESCE(SUM(batch_items.available_qty), 0) as qty')
            )
            ->whereNull('products.deleted_at')
            ->whereNull('batch_items.deleted_at')
            ->groupBy('products.id', 'products.name', 'categories.name', 'products.price')
            ->get()
            ->filter(fn($product) => $product->qty > 0)
            ->values();
    }
}
