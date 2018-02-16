<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class PlayGameController extends Controller
{
    public function index()
    {
        $game = Auth::user()->getActiveGame();

        return view('play', [
            'phrase' => $game->getDisplayPhrase(),
            'guesses' => $game->getActiveRound()->getIncorretGuesses(),
            'rounds' => $game->rounds,
        ]);
    }
}
