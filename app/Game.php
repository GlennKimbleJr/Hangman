<?php

namespace App;

use App\Phrase;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    public const TOTAL_ROUNDS = 10;
    public const MIN_LETTER_COUNT = 5;

    public function rounds()
    {
        return $this->hasMany(Round::class);
    }

    public function isComplete()
    {
        return $this->rounds->reject(function ($round) {
            return $round->isComplete();
        })->isEmpty();
    }

    public function createRounds()
    {
        $phrases = Phrase::forGame(self::MIN_LETTER_COUNT)->limit(self::TOTAL_ROUNDS)->get();

        if ($phrases->count() < self::TOTAL_ROUNDS) {
            return false;
        }

        $phrases->each(function ($phrase) {
            $phrase->addToGame($this);
        });

        return true;
    }
}
