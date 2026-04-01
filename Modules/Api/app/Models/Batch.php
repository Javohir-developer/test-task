<?php

namespace Modules\Api\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Batch extends Model
{
    use SoftDeletes;

    protected $fillable = ['provider_id'];

    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }

    public function items()
    {
        return $this->hasMany(BatchItem::class);
    }
}
