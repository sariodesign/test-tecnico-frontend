<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\ApiTestCase;

class AuthTest extends ApiTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpPersonalAccessClient();
    }

    public function test_login_returns_token_and_user_with_valid_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => Hash::make('secret123'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'user@example.com',
            'password' => 'secret123',
        ]);

        $response->assertOk()
            ->assertJsonStructure(['token', 'user' => ['id', 'name', 'email']])
            ->assertJsonPath('user.id', $user->id);

        $this->assertNotEmpty($response->json('token'));
    }

    public function test_login_fails_with_wrong_password(): void
    {
        User::factory()->create([
            'email' => 'user@example.com',
            'password' => Hash::make('secret123'),
        ]);

        $this->postJson('/api/login', [
            'email' => 'user@example.com',
            'password' => 'wrong',
        ])->assertStatus(422)->assertJsonValidationErrors('email');
    }

    public function test_login_fails_for_unknown_email(): void
    {
        $this->postJson('/api/login', [
            'email' => 'nobody@example.com',
            'password' => 'secret123',
        ])->assertStatus(422)->assertJsonValidationErrors('email');
    }

    public function test_login_requires_email_and_password(): void
    {
        $this->postJson('/api/login', [])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['email', 'password']);
    }

    public function test_logout_revokes_the_current_token(): void
    {
        $user = User::factory()->create();
        $created = $user->createToken('api');
        $token = $created->accessToken;

        $this->withHeader('Authorization', "Bearer {$token}")
            ->postJson('/api/logout')
            ->assertOk()
            ->assertJson(['message' => 'Logged out.']);

        // The access token is marked revoked in the database.
        $this->assertDatabaseHas('oauth_access_tokens', [
            'id' => $created->token->id,
            'revoked' => true,
        ]);
    }

    public function test_logout_requires_authentication(): void
    {
        $this->postJson('/api/logout')->assertUnauthorized();
    }
}
