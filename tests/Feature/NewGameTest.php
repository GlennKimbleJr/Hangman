<?php

namespace Tests\Feature;

use App\User;
use App\Round;
use App\Phrase;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NewGameTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_guest_cannot_start_a_new_game()
    {
        $response = $this->post(route('new-game'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function a_user_can_start_a_new_game()
    {
        factory(Phrase::class, 10)->create();

        $user = factory(User::class)->create();

        $response = $this->createNewGame($user);

        $response->assertRedirect(route('play'));
        $this->assertCount(1, $user->fresh()->games);
    }

    /** @test */
    public function a_user_cannot_start_a_new_game_if_an_existing_game_is_in_progress()
    {
        $user = factory(User::class)->create();
        $game = $user->games()->create([]);
        $round = factory(Round::class)->create();
        $round->game()->associate($game)->save();

        $response = $this->createNewGame($user);

        $response->assertSessionHas('error');
        $this->assertCount(1, $user->fresh()->games);
    }

    /** @test */
    public function a_new_game_will_consist_of_10_rounds()
    {
        factory(Phrase::class, 10)->create();

        $user = factory(User::class)->create();

        $response = $this->createNewGame($user);

        $this->assertCount(10, $user->fresh()->games->first()->rounds);
    }

    /** @test */
    public function a_new_game_will_not_be_created_if_there_are_not_enough_qualifying_phrases_for_10_rounds()
    {
        factory(Phrase::class, 9)->create();

        $user = factory(User::class)->create();

        $response = $this->createNewGame($user);

        $response->assertSessionHas('error');
        $this->assertCount(0, $user->fresh()->games);
    }
}
