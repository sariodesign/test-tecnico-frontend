<?php

namespace Tests\Feature;

use Tests\ApiTestCase;

class SettingsTest extends ApiTestCase
{
    public function test_settings_are_public_and_return_the_expected_shape(): void
    {
        $this->getJson('/api/settings')
            ->assertOk()
            ->assertJsonPath('data.app_name', 'Vivi Bugliano')
            ->assertJsonStructure([
                'data' => [
                    'app_name', 'description', 'favicon', 'logo', 'contact_email',
                    'social' => ['twitter', 'facebook', 'instagram'],
                ],
            ]);
    }
}
