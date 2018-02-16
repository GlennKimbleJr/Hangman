<?php

namespace App\Collections;

use App\Game;
use Illuminate\Support\Collection;

class PhraseCollection extends Collection
{
    public function hasTooFewRounds(int $expected)
    {
        return $this->count() < $expected;
    }
}
