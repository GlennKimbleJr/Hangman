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
            'letter' => $request->guess,
            'is_correct' => $isCorrect,
        ]);

        Session::flash(
            $isCorrect ? 'success' : 'error',
            $isCorrect ? 'success' : 'miss'
        );

        return redirect()->to(route('play'));
    }
}
