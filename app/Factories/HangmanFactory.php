<?php

namespace App\Factories;

use App\User;
use App\Exceptions\ErrorCreatingGameException;

class HangmanFactory
{
    public static function create(User $user)
    {
        if ($user->hasGameInProgress()) {
            throw new ErrorCreatingGameException(
                'You cannot start a new game with an existing game in progress.'
            );
        }

        $game = $user->games()->create([]);
        
        if (!$game->createRounds()) {
            $game->delete();

            throw new ErrorCreatingGameException(
                'There are not enough phrases needed for the required number of rounds.'
            );
        }

        return $game;
    }
}
