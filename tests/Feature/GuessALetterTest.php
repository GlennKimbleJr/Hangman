<?php

namespace Tests\Feature;

use App\User;
use App\Phrase;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GuessALetterTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        factory(Phrase::class, 10)->create();

        $this->user = factory(User::class)->create();
    }

    protected function guessALetter($letter = null)
    {
        $response = $this->actingAs($this->user)->post(route('guess-letter'), [
            'guess' => $letter,
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
        $response = $this->post(route('guess-letter'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function a_user_cannot_guess_without_a_game_in_progress()
    {
        $this->assertFalse($this->user->hasGameInProgress());

        $response = $this->guessALetter('A');

        $response->assertStatus(302);
        $response->assertRedirect(route('home'));
        $response->assertSessionHas('error');
    }

    /** @test */
    public function a_guess_is_required()
    {
        $this->createNewGame($this->user);

        $response = $this->guessALetter('');

        $response->assertStatus(302);
        $response->assertSessionHasErrors('guess');
    }

    /** @test */
    public function a_guess_must_be_a_letter()
    {
        $this->createNewGame($this->user);

        $response = $this->guessALetter('1');

        $response->assertStatus(302);
        $response->assertSessionHasErrors('guess');
    }

    /** @test */
    public function a_guess_must_be_one_character_long()
    {
        $this->createNewGame($this->user);

        $response = $this->guessALetter('ab');

        $response->assertStatus(302);
        $response->assertSessionHasErrors('guess');
    }

    /** @test */
    public function a_correct_guess_will_be_stored_as_true()
    {
        $this->createNewGame($this->user);
        $this->setActiveRoundPhrase('test');

        $response = $this->guessALetter('t');

        $guess = $this->user->fresh()->getActiveGame()->getActiveRound()->guesses;
        $this->assertCount(1, $guess);
        $this->assertTrue($guess->first()->is_correct);
    }

    /** @test */
    public function a_incorrect_guess_will_be_stored_as_false()
    {
        $this->createNewGame($this->user);
        $this->setActiveRoundPhrase('test');

        $response = $this->guessALetter('a');

        $guess = $this->user->fresh()->getActiveGame()->getActiveRound()->guesses;
        $this->assertCount(1, $guess);
        $this->assertFalse($guess->first()->is_correct);
    }

    /** @test */
    public function after_seven_incorrect_guess_the_round_will_be_completed_and_marked_as_lost()
    {
        $this->createNewGame($this->user);
        $this->setActiveRoundPhrase('test');

        for ($i=0; $i<7; $i++) {
            $this->guessALetter('a');
        }

        $round = $this->user->getActiveGame()->rounds->first();
        $this->assertTrue($round->isComplete());
        $this->assertFalse($round->won);

        $guesses = $round->guesses;
        $this->assertCount(7, $guesses);
        $guesses->each(function ($guess) {
            $this->assertFalse($guess->is_correct);
        });
    }

    /** @test */
    public function if_all_the_letters_in_a_phrase_are_correctly_guessed_the_round_will_be_completed_and_marked_as_won()
    {
        $this->createNewGame($this->user);
        $this->setActiveRoundPhrase('test');

        $this->guessALetter('t');
        $this->guessALetter('e');
        $this->guessALetter('s');

        $round = $this->user->getActiveGame()->rounds->first();
        $this->assertTrue($round->isComplete());
        $this->assertTrue($round->won);
    }
}
