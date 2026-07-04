<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * Create an admin user.
     */
    protected function createAdminUser(array $attributes = []): \App\Models\User
    {
        $this->seed(\Database\Seeders\RoleSeeder::class);
        $user = \App\Models\User::factory()->create($attributes);
        $adminRole = \App\Models\Role::where('name', 'admin')->first();
        if ($adminRole) {
            $user->roles()->attach($adminRole->id);
        }
        return $user;
    }
}
