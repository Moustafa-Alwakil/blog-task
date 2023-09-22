<?php

namespace Tests\Traits;

use Illuminate\Testing\TestResponse;
use Illuminate\Http\UploadedFile;

trait InteractsWithProfile
{
    public function updateProfileInformation(): TestResponse
    {
        return $this->json('PUT', '/api/profile', [
            'name' => fake()->name(),
            'email' => fake()->email(),
            'current_password' => 'password',
            'new_password' => $password = fake()->password(8),
            'new_password_confirmation' => $password,
            'avatar' => UploadedFile::fake()->image('avatar.jpg'),
        ]);
    }
}
