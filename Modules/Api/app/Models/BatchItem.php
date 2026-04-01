<?php

namespace Modules\Api\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BatchItem extends Model
{
    use SoftDeletes;

    protected $fillable = ['batch_id', 'product_id', 'storage_id', 'qty', 'price'];

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function storage()
    {
        return $this->belongsTo(Storage::class);
    }

    public function providerRefunds()
    {
        return $this->hasMany(ProviderRefund::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
