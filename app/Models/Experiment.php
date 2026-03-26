<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Experiment extends Model
{
    protected $fillable = [
        'title',
        'description',
        'category',
        'start_date',
        'end_date',
        'status',
        'archived',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function scopeNotArchived($query)
    {
        return $query->where('archived', false);
    }

    public function entries()
    {
        return $this->hasMany(ExperimentEntry::class)->orderBy('entry_date', 'desc');
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
}
