<?php

namespace Modules\Api\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Provider extends Model
{
    use SoftDeletes;

    protected $fillable = ['name'];

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function batches()
    {
        return $this->hasMany(Batch::class);
    }
}
