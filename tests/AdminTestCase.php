<?php

namespace Tests;

use App\Models\User;
use Database\Seeders\ShieldSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

abstract class AdminTestCase extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(ShieldSeeder::class);
    }

    protected function admin(): User
    {
        $user = User::factory()->create();

        $user->assignRole('super_admin');

        return $user;
    }
}
