<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\User;
use Laravel\Passport\Passport;
use Tests\ApiTestCase;

class FavoriteTest extends ApiTestCase
{
    public function test_favorites_endpoints_require_authentication(): void
    {
        $event = Event::factory()->create();

        $this->getJson('/api/favorites')->assertUnauthorized();
        $this->postJson("/api/events/{$event->id}/favorite")->assertUnauthorized();
        $this->deleteJson("/api/events/{$event->id}/favorite")->assertUnauthorized();
    }

    public function test_lists_only_the_users_favorites(): void
    {
        $user = User::factory()->create();
        $mine = Event::factory()->create();
        $other = Event::factory()->create();
        $user->favorites()->attach($mine);
        Passport::actingAs($user);

        $this->getJson('/api/favorites')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $mine->id);
    }

    public function test_store_adds_an_event_to_favorites(): void
    {
        $user = User::factory()->create();
        $event = Event::factory()->create();
        Passport::actingAs($user);

        $this->postJson("/api/events/{$event->id}/favorite")
            ->assertCreated()
            ->assertJson(['message' => 'Event added to favorites.']);

        $this->assertDatabaseHas('event_user', [
            'user_id' => $user->id,
            'event_id' => $event->id,
        ]);
    }

    public function test_store_is_idempotent(): void
    {
        $user = User::factory()->create();
        $event = Event::factory()->create();
        Passport::actingAs($user);

        $this->postJson("/api/events/{$event->id}/favorite")->assertCreated();
        $this->postJson("/api/events/{$event->id}/favorite")->assertCreated();

        $this->assertSame(1, $user->favorites()->count());
    }

    public function test_destroy_removes_an_event_from_favorites(): void
    {
        $user = User::factory()->create();
        $event = Event::factory()->create();
        $user->favorites()->attach($event);
        Passport::actingAs($user);

        $this->deleteJson("/api/events/{$event->id}/favorite")
            ->assertOk()
            ->assertJson(['message' => 'Event removed from favorites.']);

        $this->assertDatabaseMissing('event_user', [
            'user_id' => $user->id,
            'event_id' => $event->id,
        ]);
    }

    public function test_favoriting_a_missing_event_returns_404(): void
    {
        Passport::actingAs(User::factory()->create());

        $this->postJson('/api/events/999999/favorite')->assertNotFound();
    }
}
