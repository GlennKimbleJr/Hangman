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
        return (bool) !$this->getActiveRound();
    }

    public function getActiveRound()
    {
        return $this->rounds->reject(function ($round) {
            return $round->isComplete();
        })->first();
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

    public function getDisplayPhrase()
    {
        $correctGuesses = $this->getActiveRound()->guesses->filter(function ($guess) {
            return $guess->is_correct;
        })->pluck('guess')->toArray();

        $phrase = str_split($this->getActiveRoundPhrase()->text);
        foreach ($phrase as $key=>$guess) {
            if ($guess == ' ' || in_array($guess, $correctGuesses)) {
                continue;
            }

            $phrase[$key] = '_';
        }

        return implode('', $phrase);
    }

    private function getActiveRoundPhrase()
    {
        return $this->getActiveRound()->phrase;
    }

    public function guessLetter($letter)
    {
        return strpos(strtoupper($this->getActiveRoundPhrase()->text), strtoupper($letter)) !== false;
    }

    public function guessPhrase($phrase)
    {
        return strtoupper($this->getActiveRoundPhrase()->text) == strtoupper($phrase);
    }
}
