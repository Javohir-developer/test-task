<?php

namespace Modules\Api\Services;

use Illuminate\Support\Facades\DB;
use Modules\Api\Models\Batch;

class ReportService
{
    /**
     * Calculates remaining quantities of products in storage within a specified date
     * @param string $date (Y-m-d H:i:s)
     * @return \Illuminate\Support\Collection
     */
    public function getRemainingQuantities(string $date)
    {
        // For each storage/product pair, the inventory up to $date is:
        // Sum of purchases (batch_items)
        // Minus provider_refunds
        // Minus order_items
        // Plus client_refunds
        
        // This is a complex calculation via DB::select for performance across multiple tables
        
        $sql = "
            SELECT 
                bi.storage_id,
                s.name as storage_name,
                bi.product_id,
                p.name as product_name,
                (
                    SUM(bi.qty) 
                    - COALESCE((SELECT SUM(pr.qty) FROM provider_refunds pr WHERE pr.batch_item_id = bi.id AND pr.created_at <= ? AND pr.deleted_at IS NULL), 0)
                    - COALESCE((SELECT SUM(oi.qty) FROM order_items oi WHERE oi.batch_item_id = bi.id AND oi.created_at <= ? AND oi.deleted_at IS NULL), 0)
                    + COALESCE((
                        SELECT SUM(cr.qty) 
                        FROM client_refunds cr 
                        JOIN order_items oi2 ON cr.order_item_id = oi2.id 
                        WHERE oi2.batch_item_id = bi.id AND cr.created_at <= ? AND cr.deleted_at IS NULL AND oi2.deleted_at IS NULL
                    ), 0)
                ) as remaining_qty
            FROM batch_items bi
            JOIN products p ON bi.product_id = p.id
            JOIN storages s ON bi.storage_id = s.id
            WHERE bi.created_at <= ? AND bi.deleted_at IS NULL
            GROUP BY bi.id, bi.storage_id, s.name, bi.product_id, p.name
        ";
        
        // As batch_items can have multiple entries for the same product/storage, we group the results further via PHP Collection
        $results = DB::select($sql, [$date, $date, $date, $date]);
        
        return collect($results)
            ->groupBy(function($item) {
                return $item->storage_id . '_' . $item->product_id;
            })
            ->map(function($group) {
                return [
                    'storage_name' => $group->first()->storage_name,
                    'product_name' => $group->first()->product_name,
                    'remaining' => $group->sum('remaining_qty')
                ];
            })
            ->values();
    }

    /**
     * Calculates profit for each batch, factoring in refunds
     * Profit = (Sold QTY - Client Refunded QTY) * Output Price - (Sold QTY - Client Refunded QTY) * Purchase Price
     * @return \Illuminate\Support\Collection
     */
    public function getProfitPerBatch()
    {
        // The business rule defined profit as based on sold (order) items matching the exact batch item purchased.
        // Unsold stock doesn't count against realized profit immediately, but purchase costs apply to sold items.
        // We calculate per batch.
        
        $sql = "
            SELECT 
                b.id as batch_id,
                b.provider_id,
                prov.name as provider_name,
                b.created_at,
                SUM(
                    (oi.qty - COALESCE(
                        (SELECT SUM(cr.qty) FROM client_refunds cr WHERE cr.order_item_id = oi.id AND cr.deleted_at IS NULL), 0
                    )) * (oi.price - bi.price)
                ) as profit
            FROM batches b
            JOIN batch_items bi ON b.id = bi.batch_id
            JOIN order_items oi ON bi.id = oi.batch_item_id
            JOIN providers prov ON b.provider_id = prov.id
            WHERE b.deleted_at IS NULL AND bi.deleted_at IS NULL AND oi.deleted_at IS NULL
            GROUP BY b.id, b.provider_id, prov.name, b.created_at
        ";
        
        return collect(DB::select($sql))->map(function($data) {
            $data->profit = (float) $data->profit;
            return $data;
        });
    }
}
