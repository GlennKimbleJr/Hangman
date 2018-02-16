<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\GuessLetterRequest;

class GuessLetterController extends Controller
{
    public function store(GuessLetterRequest $request)
    {
        $user = Auth::user();

        if (!$user->hasGameInProgress()) {
            Session::flash('error', 'You must first start a game.');

            return redirect()->to(route('home'));
        }

        $game = $user->getActiveGame();
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
        if (!$isCorrect && $game->getActiveRound()->maxGuessesReached()) {
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
