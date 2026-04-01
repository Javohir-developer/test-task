<?php

namespace Modules\Api\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'category_id', 'price'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function batchItems()
    {
        return $this->hasMany(BatchItem::class);
    }
}
