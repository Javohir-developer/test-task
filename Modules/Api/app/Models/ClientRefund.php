<?php

namespace Modules\Api\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientRefund extends Model
{
    use SoftDeletes;

    protected $fillable = ['order_item_id', 'qty'];

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }
}
