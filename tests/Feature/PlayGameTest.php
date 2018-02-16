<?php

namespace Tests\Feature;

use App\User;
use App\Phrase;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PlayGameTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        factory(Phrase::class, 10)->create();

        $this->user = factory(User::class)->create();
    }

    protected function visitPlayGamePage()
    {
        return $this->actingAs($this->user)->get(route('play'));
    }

    /** @test */
    public function a_guest_cannot_play_a_game()
    {
        $response = $this->post(route('play'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function a_user_cannot_play_a_game_without_a_game_in_progress()
    {
        $this->assertFalse($this->user->hasGameInProgress());

        $response = $this->visitPlayGamePage();

        $response->assertStatus(302);
        $response->assertRedirect(route('home'));
        $response->assertSessionHas('error');
    }

    /** @test */
    public function the_phrase_for_the_current_round_will_be_displayed()
    {
        $this->createNewGame($this->user);

        $response = $this->visitPlayGamePage();

        $response->assertStatus(200);
        $response->assertSee($this->user->getActiveGame()->getDisplayPhrase());
    }

    /** @test */
    public function incorrect_guesses_will_be_displayed()
    {
        $this->createNewGame($this->user);

        $this->actingAs($this->user)->post(route('guess-phrase'), [
            'guess' => 'asdf',
        ]);

        $response = $this->visitPlayGamePage();

        $response->assertStatus(200);
        $response->assertSee('ASDF');
    }

    /** @test */
    public function round_results_will_be_displayed()
    {
        $this->createNewGame($this->user);
        $game = $this->user->getActiveGame();
        $game->getActiveRound()->markAsWon();
        $phrase = $game->rounds->first()->phrase->text;

        $response = $this->visitPlayGamePage();

        $response->assertStatus(200);
        $response->assertSee('Results');
        $response->assertSee('badge-success');
        $response->assertSee($phrase);
    }
}
