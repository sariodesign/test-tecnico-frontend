<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\User;
use Laravel\Passport\Passport;
use Tests\ApiTestCase;

class EventTest extends ApiTestCase
{
    public function test_omits_is_favorite_when_unauthenticated(): void
    {
        Event::factory()->create();

        $this->getJson('/api/events')
            ->assertOk()
            ->assertJsonMissingPath('data.0.is_favorite');
    }

    public function test_flags_favorites_for_authenticated_user(): void
    {
        $user = User::factory()->create();
        $favorite = Event::factory()->create(['title' => 'A favorite']);
        Event::factory()->create(['title' => 'B other']);
        $user->favorites()->attach($favorite);
        Passport::actingAs($user);

        $this->getJson('/api/events?sort=title')
            ->assertOk()
            ->assertJsonPath('data.0.is_favorite', true)
            ->assertJsonPath('data.1.is_favorite', false);
    }

    public function test_events_endpoint_is_public(): void
    {
        Event::factory()->count(2)->create();

        $this->getJson('/api/events')->assertOk();
    }

    public function test_lists_events_paginated(): void
    {
        Event::factory()->count(3)->create();

        $this->getJson('/api/events')
            ->assertOk()
            ->assertJsonCount(3, 'data')
            ->assertJsonStructure([
                'data' => [['id', 'title', 'location', 'starts_at', 'is_published']],
                'meta' => ['current_page', 'total', 'per_page'],
            ]);
    }

    public function test_respects_per_page(): void
    {
        Event::factory()->count(5)->create();

        $this->getJson('/api/events?per_page=2')
            ->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonPath('meta.per_page', 2)
            ->assertJsonPath('meta.total', 5);
    }

    public function test_filters_by_is_published(): void
    {
        Event::factory()->count(2)->create(['is_published' => true]);
        Event::factory()->count(3)->create(['is_published' => false]);

        $this->getJson('/api/events?filter[is_published]=1')
            ->assertOk()
            ->assertJsonCount(2, 'data');
    }

    public function test_filters_by_title(): void
    {
        Event::factory()->create(['title' => 'Concerto di Natale']);
        Event::factory()->create(['title' => 'Sagra del paese']);

        $this->getJson('/api/events?filter[title]=Concerto')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.title', 'Concerto di Natale');
    }

    public function test_sorts_by_title(): void
    {
        Event::factory()->create(['title' => 'Bravo']);
        Event::factory()->create(['title' => 'Alfa']);

        $this->getJson('/api/events?sort=title')
            ->assertOk()
            ->assertJsonPath('data.0.title', 'Alfa')
            ->assertJsonPath('data.1.title', 'Bravo');
    }

    public function test_rejects_disallowed_sort(): void
    {
        Event::factory()->create();

        // spatie/query-builder throws (400) on a sort that is not allow-listed.
        $this->getJson('/api/events?sort=description')->assertStatus(400);
    }

    public function test_filters_by_category(): void
    {
        Event::factory()->count(2)->create(['category' => 'musica']);
        Event::factory()->create(['category' => 'sport']);

        $this->getJson('/api/events?filter[category]=musica')
            ->assertOk()
            ->assertJsonCount(2, 'data');
    }

    public function test_lists_distinct_categories_sorted(): void
    {
        Event::factory()->count(2)->create(['category' => 'sport']);
        Event::factory()->create(['category' => 'cultura']);

        $this->getJson('/api/events/categories')
            ->assertOk()
            ->assertExactJson(['data' => ['cultura', 'sport']]);
    }

    public function test_categories_omit_unpublished(): void
    {
        Event::factory()->create(['category' => 'musica', 'is_published' => true]);
        Event::factory()->create(['category' => 'sport', 'is_published' => false]);

        $this->getJson('/api/events/categories')
            ->assertOk()
            ->assertExactJson(['data' => ['musica']]);
    }
}
