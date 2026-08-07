<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\ClientRepository;

abstract class ApiTestCase extends TestCase
{
    use RefreshDatabase;

    /** Create the personal access client so User::createToken() works in tests. */
    protected function setUpPersonalAccessClient(): void
    {
        app(ClientRepository::class)->createPersonalAccessGrantClient('Test Personal Access Client');
    }
}
