<?php

namespace Modules\Api\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProviderRefund extends Model
{
    use SoftDeletes;

    protected $fillable = ['batch_item_id', 'product_id', 'qty'];

    public function batchItem()
    {
        return $this->belongsTo(BatchItem::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
