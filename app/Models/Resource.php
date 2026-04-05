<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    protected $fillable = ['name', 'image_url', 'product_url', 'linkable_type', 'linkable_id'];

    public function getImageUrlAttribute(?string $value): ?string
    {
        if (! $value) {
            return null;
        }

        if (preg_match('/^(https?:)?\/\//i', $value)) {
            return $value;
        }

        return asset(ltrim($value, '/'));
    }

    public function getRawImagePath(): ?string
    {
        return $this->getRawOriginal('image_url');
    }

    public function linkable()
    {
        return $this->morphTo();
    }
}
