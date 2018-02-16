<?php

namespace Tests\Unit;

use App\User;
use App\Phrase;
use Tests\TestCase;
use App\Factories\HangmanFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HangmanFactoryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function ten_rounds_will_be_created_for_a_game()
    {
        factory(Phrase::class, 10)->create();

        $user = factory(User::class)->create();
        $phraseCollector = Phrase::forGame(HangmanFactory::MIN_LETTER_COUNT, HangmanFactory::TOTAL_ROUNDS);
        HangmanFactory::create($user, $phraseCollector);

        $this->assertCount(10, $user->fresh()->getActiveGame()->rounds);
    }

    /**
     * @test
     * @expectedException App\Exceptions\ErrorCreatingGameException
     */
    public function an_exception_is_thrown_if_there_are_less_than_10_phrases()
    {
        factory(Phrase::class, 9)->create();

        $user = factory(User::class)->create();
        $phraseCollector = Phrase::forGame(HangmanFactory::MIN_LETTER_COUNT, HangmanFactory::TOTAL_ROUNDS);

        HangmanFactory::create($user, $phraseCollector);
    }
}
