<?php

namespace Modules\Api\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Storage extends Model
{
    use SoftDeletes;

    protected $fillable = ['name'];

    public function batchItems()
    {
        return $this->hasMany(BatchItem::class);
    }
}
