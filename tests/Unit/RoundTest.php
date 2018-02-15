<?php

namespace Tests\Unit;

use App\Round;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RoundTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function is_complete_returns_false_if_winner_is_null()
    {
        $round = factory(Round::class)->create([
            'winner' => null,
        ]);

        $this->assertFalse($round->isComplete());
    }

    /** @test */
    public function is_complete_returns_true_if_winner_is_true()
    {
        $round = factory(Round::class)->create([
            'winner' => true,
        ]);

        $this->assertTrue($round->isComplete());
    }

    /** @test */
    public function is_complete_returns_true_if_winner_is_false()
    {
        $round = factory(Round::class)->create([
            'winner' => false,
        ]);

        $this->assertTrue($round->isComplete());
    }
}
