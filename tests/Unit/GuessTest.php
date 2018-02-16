<?php

namespace Tests\Unit;

use App\Guess;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GuessTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function the_letter_attribute_always_returns_upper_case()
    {
        $guess = factory(Guess::class)->make([
            'letter' => 'a',
        ]);

        $this->assertEquals('A', $guess->letter);
        $this->assertNotEquals('a', $guess->letter);
    }
}
