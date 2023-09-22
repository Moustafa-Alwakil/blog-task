<?php

namespace Tests\Feature;

use Tests\Traits\InteractsWithProfile;
use App\Models\User;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use InteractsWithProfile;

    public function test_that_only_logged_in_user_can_update_profile(): void
    {
        $this->updateProfileInformation()
            ->assertUnauthorized();

        $user = User::factory()->create();

        $this->actingAs($user, 'sanctum');

        $this->updateProfileInformation()
            ->assertSessionDoesntHaveErrors()
            ->assertJsonStructure([
                'message',
                'user'
            ]);
    }
}
