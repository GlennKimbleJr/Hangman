<?php

namespace App\Factories;

use App\Game;
use App\User;
use App\Phrase;
use App\Collections\PhraseCollection;
use App\Exceptions\ErrorCreatingGameException;

class HangmanFactory
{
    public const MAX_INCORRECT_GUESSES = 7;

    public const MIN_LETTER_COUNT = 5;

    public const TOTAL_ROUNDS = 10;

    public static function create(User $user, PhraseCollection $phraseCollector)
    {
        if ($user->hasGameInProgress()) {
            throw new ErrorCreatingGameException(
                'You cannot start a new game with an existing game in progress.'
            );
        }

        $game = $user->games()->create([]);

        if (!self::createRounds($game, $phraseCollector)) {
            $game->delete();

            throw new ErrorCreatingGameException(
                'There are not enough phrases needed for the required number of rounds.'
            );
        }

        return $game;
    }

    private static function createRounds(Game $game, $phraseCollector)
    {
        if ($phraseCollector->hasTooFewRounds(self::TOTAL_ROUNDS)) {
            return false;
        }

        $phraseCollector->each(function ($phrase) use ($game) {
            $phrase->addToGame($game);
        });

        return true;
    }
}
