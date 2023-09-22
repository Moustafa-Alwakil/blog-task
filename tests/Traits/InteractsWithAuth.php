<?php

namespace Tests\Traits;

use Illuminate\Testing\TestResponse;
use Illuminate\Http\UploadedFile;
use App\Models\User;

trait InteractsWithAuth
{

    public function postRegisterInformation(): TestResponse
    {
        return $this->json('POST', '/api/register', [
            'name' => fake()->name(),
            'email' => fake()->email(),
            'password' => $password = fake()->password(8),
            'password_confirmation' => $password,
            'avatar' => UploadedFile::fake()->image('avatar.jpg'),
        ]);
    }

    public function postLoginInformation(string $email, string $password): TestResponse
    {
        return $this->json('POST', '/api/login', [
            'email' => $email,
            'password' => $password,
            'device_name' => 'device'
        ]);
    }

    public function loginRandomUser(): TestResponse
    {
        $user = User::factory()->create();

        return $this->postLoginInformation($user->email, 'password');
    }
}
