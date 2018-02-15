<?php

namespace App;

use App\Game;
use Illuminate\Database\Eloquent\Model;

class Round extends Model
{
    protected $guarded = [];

    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    public function isComplete()
    {
        return !is_null($this->winner);
    }
}
