<?php

namespace Tests\Feature;

use App\User;
use App\Phrase;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GuessThePhraseTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        factory(Phrase::class, 10)->create();

        $this->user = factory(User::class)->create();
    }

    protected function guessThePhrase($phrase = null)
    {
        $response = $this->actingAs($this->user)->post(route('guess-phrase'), [
            'guess' => $phrase,
        ]);

        $this->user = $this->user->fresh();

        return $response;
    }

    protected function setActiveRoundPhrase($phrase)
    {
        $this->user->getActiveGame()->getActiveRound()->phrase()->update([
            'text' => $phrase,
        ]);

        $this->user = $this->user->fresh();
    }

    /** @test */
    public function a_guest_cannot_guess()
    {
        $response = $this->post(route('guess-phrase'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function a_user_cannot_guess_without_a_game_in_progress()
    {
        $this->assertFalse($this->user->hasGameInProgress());

        $response = $this->guessThePhrase('A quick test');

        $response->assertStatus(302);
        $response->assertRedirect(route('home'));
        $response->assertSessionHas('error');
    }

    /** @test */
    public function a_guess_is_required()
    {
        $this->createNewGame($this->user);

        $response = $this->guessThePhrase('');

        $response->assertStatus(302);
        $response->assertSessionHasErrors('guess');
    }

    /** @test */
    public function a_correct_guess_will_be_stored_as_true()
    {
        $this->createNewGame($this->user);
        $this->setActiveRoundPhrase('correct phrase');

        $response = $this->guessThePhrase('correct phrase');

        $guess = $this->user->getActiveGame()->rounds->first()->guesses;
        $this->assertCount(1, $guess);
        $this->assertTrue($guess->first()->is_correct);
    }

    /** @test */
    public function a_incorrect_guess_will_be_stored_as_false()
    {
        $this->createNewGame($this->user);
        $this->setActiveRoundPhrase('correct phrase');

        $response = $this->guessThePhrase('incorrect phrase');

        $guess = $this->user->getActiveGame()->getActiveRound()->guesses;
        $this->assertCount(1, $guess);
        $this->assertFalse($guess->first()->is_correct);
    }

    /** @test */
    public function after_seven_incorrect_guess_the_round_will_be_completed_and_marked_as_lost()
    {
        $this->createNewGame($this->user);
        $this->setActiveRoundPhrase('correct phrase');

        for ($i=0; $i<7; $i++) {
            $this->guessThePhrase('test phrase');
        }

        $round = $this->user->getActiveGame()->rounds->first();
        $this->assertFalse($round->won);
        $this->assertTrue($round->isComplete());

        $guesses = $round->guesses;
        $this->assertCount(7, $guesses);
        $guesses->each(function ($guess) {
            $this->assertFalse($guess->is_correct);
        });
    }

    /** @test */
    public function if_the_phrase_is_correctly_guessed_the_round_will_be_completed_and_marked_as_won()
    {
        $this->createNewGame($this->user);
        $this->setActiveRoundPhrase('correct phrase');

        $this->guessThePhrase('correct phrase');

        $round = $this->user->getActiveGame()->rounds->first();
        $this->assertTrue($round->won);
        $this->assertTrue($round->isComplete());
    }
}
