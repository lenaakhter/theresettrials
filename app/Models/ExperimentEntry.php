<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExperimentEntry extends Model
{
    protected $fillable = [
        'experiment_id',
        'content',
        'type',
        'entry_date',
    ];

    protected $casts = [
        'entry_date' => 'datetime',
    ];

    public function experiment()
    {
        return $this->belongsTo(Experiment::class);
    }
}
