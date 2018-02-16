<?php

namespace App;

use App\Game;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function games()
    {
        return $this->hasMany(Game::class);
    }

    public function hasGameInProgress()
    {
        return (bool) $this->getActiveGame();
    }

    public function getActiveGame()
    {
        return $this->games->reject(function ($game) {
            return $game->isComplete();
        })->first();
    }
}
