<?php

namespace App;

use App\Game;
use App\Guess;
use App\Phrase;
use Illuminate\Database\Eloquent\Model;

class Round extends Model
{
    public const MAX_INCORRECT_GUESSES = 7;

    protected $guarded = [];

    protected $dates = ['completed_at'];

    protected $casts = [
        'won' => 'boolean',
    ];

    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    public function phrase()
    {
        return $this->belongsTo(Phrase::class);
    }

    public function guesses()
    {
        return $this->hasMany(Guess::class);
    }

    public function isComplete()
    {
        return (bool) $this->completed_at;
    }

    public function maxGuessesReached()
    {
        return $this->guesses()->incorrect()->count() >= self::MAX_INCORRECT_GUESSES;
    }

    public function markAsLost()
    {
        $this->update([
            'won' => false,
            'completed_at' => now(),
        ]);
    }
}
