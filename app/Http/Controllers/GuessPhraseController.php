<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\GuessPhraseRequest;

class GuessPhraseController extends Controller
{
    public function store(GuessPhraseRequest $request)
    {
        $user = Auth::user();

        if (!$user->hasGameInProgress()) {
            Session::flash('error', 'You must first start a game.');

            return redirect()->to(route('home'));
        }

        $game = $user->getActiveGame();
        $isCorrect = $game->guessPhrase($request->guess);

        $game->getActiveRound()->guesses()->create([
            'guess' => $request->guess,
            'is_correct' => $isCorrect,
        ]);

        if (!$isCorrect && $game->getActiveRound()->maxGuessesReached()) {
            Session::flash('error', 'failed');
            $game->getActiveRound()->markAsLost();

            return redirect()->to(route('play'));
        }

        if ($isCorrect) {
            $game->getActiveRound()->markAsWon();
        }

        Session::flash(
            $isCorrect ? 'success' : 'error',
            $isCorrect ? 'success' : 'miss'
        );

        return redirect()->to(route('play'));
    }
}
