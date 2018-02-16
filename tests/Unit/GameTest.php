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

    public function setUp()
    {
        parent::setUp();

        $phrase = factory(Phrase::class)->create([
            'text' => 'this is a test',
        ]);

        $game = factory(Game::class)->create();

        $this->round = $game->rounds()->create([
            'phrase_id' => $phrase->id,
        ]);

        $this->game = $game->fresh();
    }

    /** @test */
    public function is_complete_returns_false_if_any_of_the_games_rounds_are_incomplete()
    {
        $incompleteRound = factory(Round::class)->create();
        $incompleteRound->game()->associate($this->game)->save();

        $completeRound = factory(Round::class)->create([
            'completed_at' => now(),
        ]);
        $completeRound->game()->associate($this->game)->save();

        $this->assertFalse($this->game->isComplete());
    }

    /** @test */
    public function is_complete_returns_true_if_all_of_the_games_rounds_are_complete()
    {
        $this->round->update([
            'completed_at' => now(),
        ]);

        $this->assertTrue($this->game->isComplete());
    }

    /** @test */
    public function get_display_phrase_will_convert_all_alphabetical_characeters_to_an_underscore()
    {
        $expectedPhrase = '____ __ _ ____';

        $this->assertEquals($expectedPhrase, $this->game->getDisplayPhrase());
    }

    /** @test */
    public function get_display_phrase_will_show_corrected_guessed_characters()
    {
        $this->round->guesses()->create([
            'guess' => 't',
            'is_correct' => true
        ]);

        $this->assertContains('T', $this->game->getDisplayPhrase());
    }

    /** @test */
    public function guess_letter_returns_true_if_the_input_exists_in_the_current_rounds_phrase()
    {
        $this->assertTrue($this->game->guessLetter('t'));
        $this->assertFalse($this->game->guessLetter('x'));
    }

    /** @test */
    public function guess_phrase_returns_true_if_the_input_matches_the_current_rounds_phrase()
    {
        $this->assertTrue($this->game->guessPhrase('this is a test'));
        $this->assertFalse($this->game->guessPhrase('this is NOT the correct guess'));
    }
}
