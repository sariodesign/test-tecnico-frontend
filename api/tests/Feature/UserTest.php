<?php

namespace Tests\Feature;

use App\Models\User;
use Laravel\Passport\Passport;
use Tests\ApiTestCase;

class UserTest extends ApiTestCase
{
    public function test_user_endpoint_requires_authentication(): void
    {
        $this->getJson('/api/user')->assertUnauthorized();
    }

    public function test_returns_the_authenticated_user(): void
    {
        $user = User::factory()->create();
        Passport::actingAs($user);

        $this->getJson('/api/user')
            ->assertOk()
            ->assertJsonPath('data.id', $user->id)
            ->assertJsonPath('data.email', $user->email)
            ->assertJsonMissingPath('data.password');
    }
}
