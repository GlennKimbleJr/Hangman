<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class PlayGameController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (!$user->hasGameInProgress()) {
            Session::flash('error', 'You must first start a game.');

            return redirect()->to(route('home'));
        }

        return view('play', [
            'phrase' => $user->getActiveGame()->getDisplayPhrase(),
        ]);
    }
}
