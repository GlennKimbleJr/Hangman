<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Exceptions\NotEnoughPhrasesException;

class NewGameController extends Controller
{
    public function store()
    {
        if (Auth::user()->hasGameInProgress()) {
            return back()->withErrors([
                'game' => "You cannot create a new game until you've finished the one in progress.",
            ]);
        }

        $game = Auth::user()->games()->create([]);

        try {
            $game->createRounds();
        } catch (NotEnoughPhrasesException $e) {
            $game->delete();

            return back()->withErrors([
                'system' => "There was an error setting up your game, please try again later.",
            ]);
        }
    }
}
