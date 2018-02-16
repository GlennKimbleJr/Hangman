<?php

namespace App\Http\Controllers;

use App\Traits\GameOver;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\GuessLetterRequest;

class GuessLetterController extends Controller
{
    use GameOver;

    public function store(GuessLetterRequest $request)
    {
        $game = Auth::user()->getActiveGame();
        $isCorrect = $game->guessLetter($request->guess);

        $game->getActiveRound()->guesses()->create([
            'guess' => $request->guess,
            'is_correct' => $isCorrect,
        ]);

        if (!$this->gameIsComplete($game, $isCorrect, 'letter')) {
            Session::flash(
                $isCorrect ? 'success' : 'error',
                $isCorrect ? 'success' : 'miss'
            );
        }

        return redirect()->to(route('play'));
    }
}
