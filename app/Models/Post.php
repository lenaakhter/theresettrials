<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'cover_image',
        'category',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
        ];
    }

    public function scopePublished($query)
    {
        return $query->whereNotNull('published_at')->where('published_at', '<=', now());
    }

    public function getCoverImageUrlAttribute(): ?string
    {
        if (! $this->cover_image) {
            return null;
        }

        if (preg_match('/^https?:\/\//i', $this->cover_image)) {
            return $this->cover_image;
        }

        return asset(ltrim($this->cover_image, '/'));
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function resources()
    {
        return $this->morphMany(Resource::class, 'linkable');
    }
}
