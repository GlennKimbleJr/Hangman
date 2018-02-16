<?php

namespace App\Http\Controllers;

use App\Factories\HangmanFactory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\GuessPhraseRequest;

class GuessPhraseController extends Controller
{
    public function store(GuessPhraseRequest $request)
    {
        $game = Auth::user()->getActiveGame();
        $isCorrect = $game->guessPhrase($request->guess);
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

    public function gameIsLost($game, $isCorrect)
    {
        if (!$isCorrect && $game->getActiveRound()->maxGuessesReached(HangmanFactory::MAX_INCORRECT_GUESSES)) {
            Session::flash('error', 'failed');

            $game->getActiveRound()->markAsLost();

            return true;
        }

        return false;
    }

    public function gameIsWon($game, $isCorrect)
    {
        if ($isCorrect) {
            Session::flash('success', 'won');

            $game->getActiveRound()->markAsWon();

            return true;
        }

        return false;
    }
}
