<?php

namespace Tests\Unit;

use App\Guess;
use App\Round;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RoundTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function is_complete_returns_false_if_completed_at_is_null()
    {
        $round = factory(Round::class)->create([
            'completed_at' => null,
        ]);

        $this->assertFalse($round->isComplete());
    }

    /** @test */
    public function is_complete_returns_true_if_completed_at_is_not_null()
    {
        $round = factory(Round::class)->create([
            'completed_at' => now(),
        ]);

        $this->assertTrue($round->isComplete());
    }

    /** @test */
    public function max_guesses_reached_returns_true_if_there_are_7_incorrect_guesses()
    {
        $round = factory(Round::class)->create();

        for ($i=0; $i<7; $i++) {
            $round->guesses()->create(factory(Guess::class)->make([
                'is_correct' => false,
            ])->toArray());
        }

        $this->assertTrue($round->fresh()->maxGuessesReached());
    }

    /** @test */
    public function mark_as_lost_marks_a_round_as_complete_and_sets_won_to_false()
    {
        $round = factory(Round::class)->create();

        $round->markAsLost();

        $this->assertTrue($round->isComplete());
        $this->assertFalse($round->won);
    }
}
