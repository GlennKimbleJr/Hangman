<?php

namespace App;

use App\Round;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{   
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
