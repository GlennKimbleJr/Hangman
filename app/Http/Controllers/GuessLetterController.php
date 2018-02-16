<?php

namespace App\Http\Controllers;

use App\Factories\HangmanFactory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\GuessLetterRequest;

class GuessLetterController extends Controller
{
    public function store(GuessLetterRequest $request)
    {
        $game = Auth::user()->getActiveGame();
        $isCorrect = $game->guessLetter($request->guess);
        $game->getActiveRound()->guesses()->create([
            'guess' => $request->guess,
            'is_correct' => $isCorrect,
        ]);

        if (!$this->gameIsComplete($game, $isCorrect)) {
            Session::flash(
                $isCorrect ? 'success' : 'error',
                $isCorrect ? 'success' : 'miss'
            );
        }

        return redirect()->to(route('play'));
    }

    private function gameIsComplete($game, $isCorrect)
    {
        if ($this->gameIsLost($game, $isCorrect)) {
            return true;
        }

        if ($this->gameIsWon($game, $isCorrect)) {
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

    private function gameIsWon($game, $isCorrect)
    {
        if ($isCorrect && $game->getActiveRound()->allLettersGuessed()) {
            Session::flash('success', 'won');

            $game->getActiveRound()->markAsWon();

            return true;
        }

        return false;
    }
}
