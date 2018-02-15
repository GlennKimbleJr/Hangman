<?php

namespace App;

use App\User;
use App\Exceptions\NotEnoughPhrasesException;
use App\Exceptions\ErrorCreatingGameException;

class Hangman
{
    public static function create(User $user)
    {
        if ($user->hasGameInProgress()) {
            throw new ErrorCreatingGameException('You cannot start a new game with an existing game in progress.');
        }

        $game = $user->games()->create([]);

        try {
            $game->createRounds();
        } catch (NotEnoughPhrasesException $e) {
            $game->delete();

            throw new ErrorCreatingGameException($e->getMessage());
        }

        return $game;
    }
}
