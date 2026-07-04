<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Volt\Volt;
use Tests\TestCase;

class UserManagerTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_access_user_manager_page(): void
    {
        $user = $this->createAdminUser();

        $response = $this->actingAs($user)->get('/users');

        $response->assertStatus(200);
    }

    public function test_non_admin_cannot_access_user_manager_page(): void
    {
        $this->seed(\Database\Seeders\RoleSeeder::class);
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/users');

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('error', 'Unauthorised access!');
        $this->assertGuest();
    }

    public function test_users_can_be_searched(): void
    {
        $admin = $this->createAdminUser();
        
        $user1 = User::factory()->create(['name' => 'John Doe', 'email' => 'john@example.com']);
        $user2 = User::factory()->create(['name' => 'Jane Smith', 'email' => 'jane@example.com']);

        $this->actingAs($admin);

        Volt::test('user-manager')
            ->set('search', 'John')
            ->assertSee('John Doe')
            ->assertDontSee('Jane Smith');
    }

    public function test_users_can_be_filtered_by_role(): void
    {
        $admin = $this->createAdminUser();
        
        $driverRole = Role::where('name', 'driver')->first();
        $parentRole = Role::where('name', 'parent')->first();

        $user1 = User::factory()->create(['name' => 'Driver User']);
        $user1->roles()->attach($driverRole->id);

        $user2 = User::factory()->create(['name' => 'Parent User']);
        $user2->roles()->attach($parentRole->id);

        $this->actingAs($admin);

        Volt::test('user-manager')
            ->set('roleFilter', (string)$driverRole->id)
            ->assertSee('Driver User')
            ->assertDontSee('Parent User');
    }

    public function test_admin_can_create_user(): void
    {
        $admin = $this->createAdminUser();
        $parentRole = Role::where('name', 'parent')->first();

        $this->actingAs($admin);

        $component = Volt::test('user-manager')
            ->set('name', 'New User')
            ->set('email', 'newuser@example.com')
            ->set('mobile', '9876543210')
            ->set('password', 'password123')
            ->set('selectedRoles', [$parentRole->id])
            ->call('createUser');

        $component->assertHasNoErrors();

        $this->assertDatabaseHas('users', [
            'name' => 'New User',
            'email' => 'newuser@example.com',
            'mobile' => '9876543210',
        ]);

        $createdUser = User::where('email', 'newuser@example.com')->first();
        $this->assertTrue($createdUser->hasRole('parent'));
    }

    public function test_admin_can_update_user(): void
    {
        $admin = $this->createAdminUser();
        $parentRole = Role::where('name', 'parent')->first();
        $driverRole = Role::where('name', 'driver')->first();

        $user = User::factory()->create(['name' => 'Old Name']);
        $user->roles()->attach($parentRole->id);

        $this->actingAs($admin);

        $component = Volt::test('user-manager')
            ->call('openEditModal', $user)
            ->set('name', 'Updated Name')
            ->set('selectedRoles', [$driverRole->id])
            ->call('updateUser');

        $component->assertHasNoErrors();

        $user->refresh();
        $this->assertSame('Updated Name', $user->name);
        $this->assertTrue($user->hasRole('driver'));
        $this->assertFalse($user->hasRole('parent'));
    }

    public function test_admin_can_delete_user(): void
    {
        $admin = $this->createAdminUser();
        
        $user = User::factory()->create();

        $this->actingAs($admin);

        Volt::test('user-manager')
            ->call('openDeleteModal', $user)
            ->call('deleteUser');

        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
        ]);
    }
}
