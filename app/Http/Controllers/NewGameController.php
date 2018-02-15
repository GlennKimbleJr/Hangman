<?php

namespace App\Http\Controllers;

use Exception;
use App\Hangman;
use Illuminate\Support\Facades\Auth;

class NewGameController extends Controller
{
    public function store()
    {
        try {
            Hangman::create(Auth::user());

            return redirect()->to(route('play'));
        } catch (Exception $e) {
            return back()->withErrors([
                'game' => $e->getMessage(),
            ]);
        }
    }
}
