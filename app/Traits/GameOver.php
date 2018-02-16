<?php

namespace App\Traits;

use App\Factories\HangmanFactory;
use Illuminate\Support\Facades\Session;

trait GameOver
{
    private function gameIsComplete($game, $isCorrect, $guessType)
    {
        if ($this->gameIsLost($game, $isCorrect)) {
            return true;
        }

        if ($this->gameIsWon($game, $isCorrect, $guessType)) {
            return true;
        }

        return false;
    }

    private function gameIsLost($game, $isCorrect)
    {
        if (!$isCorrect && $game->getActiveRound()->maxGuessesReached(HangmanFactory::MAX_INCORRECT_GUESSES)) {
            Session::flash('error', 'failed');

            $game->getActiveRound()->markAsLost();

            return true;
        }

        return false;
    }

    private function gameIsWon($game, $isCorrect, $guessType)
    {
        $gameIsWon = ($guessType == 'phrase')
            ? $isCorrect
            : ($isCorrect && $game->getActiveRound()->allLettersGuessed());

        if ($gameIsWon) {
            Session::flash('success', 'won');

            $game->getActiveRound()->markAsWon();

            return true;
        }

        return false;
    }
}
