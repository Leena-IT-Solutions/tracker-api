<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Volt\Volt;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_page_renders_stats(): void
    {
        $user = $this->createAdminUser();

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);

        Volt::test('dashboard-stats')
            ->assertSee('Total Users')
            ->assertSee('Admins')
            ->assertSee('Managers')
            ->assertSee('Parents')
            ->assertSee('Drivers')
            ->assertSee('Attendants')
            ->assertSee('Childrens');
    }
}
