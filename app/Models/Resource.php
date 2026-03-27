<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    protected $fillable = ['name', 'image_url', 'product_url', 'linkable_type', 'linkable_id'];

    public function linkable()
    {
        return $this->morphTo();
    }
}
