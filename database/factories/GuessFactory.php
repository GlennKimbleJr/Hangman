<?php

use App\Guess;
use App\Round;
use Faker\Generator as Faker;

$factory->define(Guess::class, function (Faker $faker) {
    return [
        'round_id' => function () {
            return factory(Round::class)->create()->id;
        },
        'guess' => $faker->word(1)[0],
        'is_correct' => (bool) rand(0, 1),
    ];
});
