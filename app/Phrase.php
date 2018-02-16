<?php

namespace App;

use App\Game;
use Illuminate\Database\Eloquent\Model;

class Phrase extends Model
{
    protected $guarded = [];

    public function scopeforGame($query, $letterCount)
    {
        return $query->inRandomOrder()
            ->whereRaw("LENGTH(replace(text, ' ', '')) >= ?", [$letterCount]);
    }

    public function getTextAttribute()
    {
        return strtoupper($this->attributes['text']);
    }

    public function addToGame(Game $game)
    {
        $game->rounds()->create([
            'phrase_id' => $this->id,
        ]);

        return $this;
    }
}
