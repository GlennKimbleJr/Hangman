<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Guess extends Model
{
    protected $guarded = [];

    protected $casts = [
        'is_correct' => 'boolean',
    ];

    public function getLetterAttribute()
    {
        return strtoupper($this->attributes['letter']);
    }
}
