<?php

namespace Tests\Unit;

use App\Round;
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
}
