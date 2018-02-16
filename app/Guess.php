<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Guess extends Model
{
    protected $guarded = [];

    protected $casts = [
        'is_correct' => 'boolean',
    ];

    public function scopeIncorrect($query)
    {
        return $query->whereIsCorrect(0);
    }

    public function getGuessAttribute()
    {
        return strtoupper($this->attributes['guess']);
    }
}
