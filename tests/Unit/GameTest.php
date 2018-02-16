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

    /** @test */
    public function get_display_phrase_will_convert_all_alphabetical_characeters_to_an_underscore()
    {
        factory(Phrase::class, 10)->create();
        $game = factory(Game::class)->create();
        $game->createRounds();

        $expectedPhrase = $game->rounds->first()->phrase->text;
        $expectedPhrase = preg_replace("/[A-Za-z]/", '_', $expectedPhrase);

        $this->assertEquals($expectedPhrase, $game->getDisplayPhrase());
    }

    /** @test */
    public function get_display_phrase_will_show_corrected_guessed_characters()
    {
        factory(Phrase::class, 10)->create();
        $game = factory(Game::class)->create();
        $game->createRounds();

        $round = $game->rounds->first();
        $correctLetter = $round->phrase->text[0];
        $round->guesses()->create([
            'guess' => $correctLetter,
            'is_correct' => true
        ]);

        $this->assertContains($correctLetter, $game->getDisplayPhrase());
    }

    /** @test */
    public function guess_letter_returns_true_if_the_input_exists_in_the_current_rounds_phrase()
    {
        factory(Phrase::class, 10)->create();
        $game = factory(Game::class)->create();
        $game->createRounds();
        $game->getActiveRound()->phrase()->update([
            'text' => 'test',
        ]);

        $this->assertTrue($game->guessLetter('s'));
        $this->assertFalse($game->guessLetter('x'));
    }

    /** @test */
    public function guess_phrase_returns_true_if_the_input_matches_the_current_rounds_phrase()
    {
        factory(Phrase::class, 10)->create();
        $game = factory(Game::class)->create();
        $game->createRounds();
        $game->getActiveRound()->phrase()->update([
            'text' => 'this is the correct guess',
        ]);

        $this->assertTrue($game->guessPhrase('this is the correct guess'));
        $this->assertFalse($game->guessPhrase('this is NOT the correct guess'));
    }
}
