<?php

namespace App\Http\Controllers;

use App\Traits\GameOver;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\GuessPhraseRequest;

class GuessPhraseController extends Controller
{
    use GameOver;

    public function store(GuessPhraseRequest $request)
    {
        $game = Auth::user()->getActiveGame();
        $isCorrect = $game->guessPhrase($request->guess);

        $game->getActiveRound()->guesses()->create([
            'guess' => $request->guess,
            'is_correct' => $isCorrect,
        ]);

        if (!$this->gameIsComplete($game, $isCorrect, 'phrase')) {
            Session::flash(
                $isCorrect ? 'success' : 'error',
                $isCorrect ? 'success' : 'miss'
            );
        }

        return redirect()->to(route('play'));
    }
}
