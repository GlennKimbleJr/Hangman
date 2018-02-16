<?php

namespace Tests\Unit;

use App\User;
use App\Round;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
    }

    /** @test */
    public function has_game_in_progress_returns_true_if_any_of_a_users_games_are_incomplete()
    {
        $this->incompleteGame();
        $this->completeGame();

        $this->assertTrue($this->user->hasGameInProgress());
    }

    /** @test */
    public function has_game_in_progress_returns_false_if_all_of_a_users_games_are_complete()
    {
        $this->completeGame();

        $this->assertFalse($this->user->hasGameInProgress());
    }

    /** @test */
    public function get_active_game_returns_the_active_game()
    {
        $incompleteGame = $this->incompleteGame();
        $completeGame = $this->completeGame();

        $activeGame = $this->user->getActiveGame();
        $this->assertEquals($incompleteGame->id, $activeGame->id);
        $this->assertNotEquals($completeGame->id, $activeGame->id);
    }

    protected function incompleteGame()
    {
        return $this->createGame(false);;
    }

    protected function completeGame()
    {
        return $this->createGame(true);;
    }

    protected function createGame($complete = false)
    {
        $game = $this->user->games()->create();

        factory(Round::class)->create([
            'completed_at' => $complete ? now() : null,
        ])->game()->associate($game)->save();

        return $game;
    }
}
