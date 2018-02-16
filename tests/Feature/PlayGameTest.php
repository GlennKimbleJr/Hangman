<?php

namespace Tests\Feature;

use App\User;
use App\Phrase;
use Tests\TestCase;
use App\Factories\HangmanFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PlayGameTest extends TestCase
{
    use RefreshDatabase;

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
        $user = factory(User::class)->create();
        $this->assertFalse($user->hasGameInProgress());

        $response = $this->actingAs($user)->get(route('play'));

        $response->assertStatus(302);
        $response->assertRedirect(route('home'));
        $response->assertSessionHas('error');
    }

    /** @test */
    public function the_phrase_for_the_current_round_will_be_displayed()
    {
        $phrases = factory(Phrase::class, 10)->create();
        $user = factory(User::class)->create();
        HangmanFactory::create($user);

        $response = $this->actingAs($user->fresh())->get(route('play'));

        $response->assertStatus(200);
        $response->assertSee($user->fresh()->getActiveGame()->getDisplayPhrase());
    }

    /** @test */
    public function incorrect_guesses_will_be_displayed()
    {
        $phrases = factory(Phrase::class, 10)->create();
        $user = factory(User::class)->create();
        HangmanFactory::create($user);

        $response = $this->actingAs($user->fresh())->post(route('guess-phrase'), [
            'guess' => 'asdf',
        ]);

        $response = $this->actingAs($user->fresh())->get(route('play'));

        $response->assertStatus(200);
        $response->assertSee('ASDF');
    }
}
