<?php

namespace App;

use App\Game;
use App\Collections\PhraseCollection;
use Illuminate\Database\Eloquent\Model;

class Phrase extends Model
{
    protected $guarded = [];

    public function newCollection(array $models = [])
    {
        return new PhraseCollection($models);
    }

    public function scopeforGame($query, $letterCount, $totalRounds)
    {
        return $query->whereRaw("LENGTH(replace(text, ' ', '')) >= ?", [$letterCount])
            ->inRandomOrder()
            ->limit($totalRounds)
            ->get();
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
