<?php

namespace Modules\Api\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderItem extends Model
{
    use SoftDeletes;

    protected $fillable = ['order_id', 'product_id', 'batch_item_id', 'qty', 'price'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function batchItem()
    {
        return $this->belongsTo(BatchItem::class);
    }

    public function clientRefunds()
    {
        return $this->hasMany(ClientRefund::class);
    }
}
