<?php

namespace Tests\Unit;

use App\User;
use App\Round;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function has_game_in_progress_returns_true_if_any_of_a_users_games_are_incomplete()
    {
        $user = factory(User::class)->create();

        $incompleteGame = $user->games()->create([]);
        $incompleteRound = factory(Round::class)->create();
        $incompleteRound->game()->associate($incompleteGame)->save();

        $completeGame = $user->games()->create([]);
        $completeRound = factory(Round::class)->create([
            'winner' => true,
        ]);
        $completeRound->game()->associate($completeGame)->save();

        $this->assertTrue($user->hasGameInProgress());
    }

    /** @test */
    public function has_game_in_progress_returns_false_if_all_of_a_users_games_are_complete()
    {
        $user = factory(User::class)->create();

        $completeGame = $user->games()->create([]);
        $completeRound = factory(Round::class)->create([
            'winner' => true,
        ]);
        $completeRound->game()->associate($completeGame)->save();

        $this->assertFalse($user->hasGameInProgress());
    }
}
