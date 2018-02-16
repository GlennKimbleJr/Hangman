<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function createNewGame($user)
    {
        return $this->actingAs($user->fresh())->post(route('new-game'));
    }
}
