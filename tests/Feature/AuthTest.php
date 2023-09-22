<?php

namespace Tests\Feature;

use Tests\Traits\InteractsWithAuth;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use InteractsWithAuth;

    public function test_that_only_guests_can_register(): void
    {
        $this->postRegisterInformation()
            ->assertSessionDoesntHaveErrors()
            ->assertJsonStructure([
                'token',
                'user'
            ]);

        $this->postRegisterInformation()
            ->assertBadRequest();
    }

    public function test_that_only_guests_can_login()
    {
        $this->loginRandomUser()
            ->assertSessionDoesntHaveErrors()
            ->assertJsonStructure([
                'token',
                'user'
            ]);

        $this->postLoginInformation('random', 'random')
            ->assertBadRequest();
    }
}
