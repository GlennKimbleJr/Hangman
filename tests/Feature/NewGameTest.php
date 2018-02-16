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

    public function setUp()
    {
        parent::setUp();

        factory(Phrase::class, 10)->create();

        $this->user = factory(User::class)->create();
    }

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

        $response = $this->createNewGame($this->user);

        $response->assertRedirect(route('play'));
        $this->assertCount(1, $this->user->fresh()->games);
    }

    /** @test */
    public function a_user_cannot_start_a_new_game_if_an_existing_game_is_in_progress()
    {
        $game = $this->user->games()->create([]);
        $round = factory(Round::class)->create();
        $round->game()->associate($game)->save();

        $response = $this->createNewGame($this->user);

        $response->assertSessionHas('error');
        $this->assertCount(1, $this->user->fresh()->games);
    }

    /** @test */
    public function a_new_game_will_consist_of_10_rounds()
    {
        $response = $this->createNewGame($this->user);

        $this->assertCount(10, $this->user->games->first()->rounds);
    }

    /** @test */
    public function a_new_game_will_not_be_created_if_there_are_not_enough_qualifying_phrases_for_10_rounds()
    {
        Phrase::first()->delete();

        $response = $this->createNewGame($this->user);

        $response->assertSessionHas('error');
        $this->assertCount(0, $this->user->fresh()->games);
    }
}
