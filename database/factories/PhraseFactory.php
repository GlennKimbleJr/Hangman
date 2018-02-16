<?php

use App\Phrase;
use Faker\Generator as Faker;

$factory->define(Phrase::class, function (Faker $faker) {
    return [
        'text' => preg_replace("/[^\A-Za-z ]/", '', $faker->sentence()),
    ];
});
