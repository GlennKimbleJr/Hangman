<?php

use App\Game;
use App\Round;
use App\Phrase;
use Faker\Generator as Faker;

$factory->define(Round::class, function (Faker $faker) {
    return [
        'game_id' => function () {
            return factory(Game::class)->create()->id;
        },
        'phrase_id' => function () {
            return factory(Phrase::class)->create()->id;
        },
        'won' => false,
        'completed_at' => null,
    ];
});
