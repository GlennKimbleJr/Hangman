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
        $correctGuesses = $this->getCorrectGusses();
        $phrase = str_split($this->getActiveRoundPhrase()->text);

        return collect($phrase)->transform(function ($letter) use ($correctGuesses) {
            return $this->shouldDisplayLetter($letter, $correctGuesses) ? $letter : '_';
        })->implode('');
    }

    private function getCorrectGusses()
    {
        return $this->getActiveRound()->guesses->filter(function ($guess) {
            return $guess->is_correct;
        })->pluck('guess');
    }

    private function getActiveRoundPhrase()
    {
        return $this->getActiveRound()->phrase;
    }

    private function shouldDisplayLetter($letter, $guesses)
    {
        return $letter == ' ' || $guesses->contains($letter);
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
