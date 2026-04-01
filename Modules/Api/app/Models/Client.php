<?php

namespace Modules\Api\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use SoftDeletes;

    protected $fillable = ['name'];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
