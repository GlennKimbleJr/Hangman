<?php

namespace Tests\Unit;

use App\Guess;
use App\Round;
use App\Phrase;
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
            $this->guessALetter($round, '', false);
        }

        $this->assertTrue($round->fresh()->maxGuessesReached(7));
    }

    /** @test */
    public function mark_as_lost_marks_a_round_as_complete_and_sets_won_to_false()
    {
        $round = factory(Round::class)->create();

        $round->markAsLost();

        $this->assertTrue($round->isComplete());
        $this->assertFalse($round->won);
    }

    /** @test */
    public function mark_as_won_marks_a_round_as_complete_and_sets_won_to_true()
    {
        $round = factory(Round::class)->create();

        $round->markAsWon();

        $this->assertTrue($round->isComplete());
        $this->assertTrue($round->won);
    }

    /** @test */
    public function all_letters_guessed_returns_true_if_the_correct_guesses_reveal_all_letters_in_a_pharse()
    {
        $phrase = factory(Phrase::class)->create([
            'text' => 'correct',
        ]);

        $round = factory(Round::class)->create([
            'phrase_id' => $phrase->id,
        ]);

        $this->guessALetter($round, 'c', true);
        $this->guessALetter($round, 'o', true);
        $this->guessALetter($round, 'r', true);
        $this->guessALetter($round, 'e', true);
        $this->guessALetter($round, 't', true);

        $this->assertTrue($round->fresh()->allLettersGuessed());
    }

    protected function guessALetter($round, $letter, $correct)
    {
        $round->guesses()->create(factory(Guess::class)->make([
            'guess' => $letter,
            'is_correct' => $correct,
        ])->toArray());
    }
}
