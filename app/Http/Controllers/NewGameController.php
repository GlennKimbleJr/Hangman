<?php

namespace App\Http\Controllers;

use App\Phrase;
use App\Factories\HangmanFactory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Exceptions\ErrorCreatingGameException;

class NewGameController extends Controller
{
    public function store()
    {
        try {
            HangmanFactory::create(
                Auth::user(),
                Phrase::forGame(HangmanFactory::MIN_LETTER_COUNT, HangmanFactory::TOTAL_ROUNDS)
            );

            Session::flash('success', "You've started a new game!");

            return redirect()->to(route('play'));
        } catch (ErrorCreatingGameException $e) {
            Session::flash('error', $e->getMessage());

            return back();
        }
    }
}
