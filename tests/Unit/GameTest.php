<?php

namespace Tests\Unit;

use App\Game;
use App\Round;
use App\Phrase;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GameTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function is_complete_returns_false_if_any_of_the_games_rounds_are_incomplete()
    {
        $game = factory(Game::class)->create();

        $incompleteRound = factory(Round::class)->create();
        $incompleteRound->game()->associate($game)->save();

        $completeRound = factory(Round::class)->create([
            'winner' => true,
        ]);
        $completeRound->game()->associate($game)->save();

        $this->assertFalse($game->isComplete());
    }

    /** @test */
    public function is_complete_returns_true_if_all_of_the_games_rounds_are_complete()
    {
        $game = factory(Game::class)->create();

        $completeRound = factory(Round::class)->create([
            'winner' => true,
        ]);

        $completeRound->game()->associate($game)->save();

        $this->assertTrue($game->isComplete());
    }

    /** @test */
    public function create_rounds_will_create_10_rounds_for_a_game()
    {
        factory(Phrase::class, 10)->create();

        $game = factory(Game::class)->create();

        $game->createRounds();

        $this->assertCount(10, $game->fresh()->rounds);
    }

    /** @test */
    public function create_rounds_returns_false_if_there_are_less_than_10_phrases_found()
    {
        factory(Phrase::class, 9)->create();

        $game = factory(Game::class)->create();

        $this->assertFalse($game->createRounds());
    }
}
