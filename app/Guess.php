<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Guess extends Model
{
    protected $guarded = [];

    public function getLetterAttribute()
    {
        return strtoupper($this->attributes['letter']);
    }
}
