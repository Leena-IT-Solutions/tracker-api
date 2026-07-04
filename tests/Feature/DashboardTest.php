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

    public function test_system_updater_renders_on_dashboard_and_runs_successfully(): void
    {
        \Illuminate\Support\Facades\Process::fake([
            'git config *' => \Illuminate\Support\Facades\Process::result(''),
            'git -c safe.directory=* stash *' => \Illuminate\Support\Facades\Process::result(''),
            'git -c safe.directory=* log -1 *' => \Illuminate\Support\Facades\Process::result('abc1234 - Test commit'),
            'git -c safe.directory=* pull --no-edit origin main' => \Illuminate\Support\Facades\Process::result('Already up to date.'),
            'php artisan migrate *' => \Illuminate\Support\Facades\Process::result('Nothing to migrate.'),
            'php artisan optimize:clear' => \Illuminate\Support\Facades\Process::result('Caches cleared.'),
        ]);

        $user = $this->createAdminUser();

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertSeeLivewire('system-updater');

        Volt::test('system-updater')
            ->assertSee('System Updates')
            ->assertSee('Current commit:')
            ->call('updateApp')
            ->assertSee('Application updated successfully!');
    }
}
