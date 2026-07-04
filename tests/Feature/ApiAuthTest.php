<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use App\Mail\OtpMail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ApiAuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RoleSeeder::class);
    }

    public function test_user_can_register_via_api_and_gets_parent_role(): void
    {
        $response = $this->postJson('/api/register', [
            'name' => 'New User',
            'email' => 'newuser@example.com',
            'mobile' => '9876543210',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(201);
        $response->assertJsonStructure(['token', 'user' => ['id', 'name', 'email', 'mobile', 'roles']]);

        $this->assertDatabaseHas('users', [
            'email' => 'newuser@example.com',
            'mobile' => '9876543210',
        ]);

        $user = User::where('email', 'newuser@example.com')->first();
        $this->assertTrue($user->hasRole('parent'));
    }

    public function test_user_can_login_via_api_with_email(): void
    {
        $user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'user@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['token', 'user']);
    }

    public function test_user_can_login_via_api_with_mobile(): void
    {
        $user = User::factory()->create([
            'mobile' => '9664588677',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => '9664588677',
            'password' => 'password123',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['token', 'user']);
    }

    public function test_forgot_password_sends_otp_email(): void
    {
        Mail::fake();

        $user = User::factory()->create(['email' => 'user@example.com']);

        $response = $this->postJson('/api/forgot-password', [
            'email' => 'user@example.com',
        ]);

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Verification code sent to your email.']);

        $this->assertDatabaseHas('password_reset_otps', [
            'email' => 'user@example.com',
        ]);

        Mail::assertSent(OtpMail::class, function ($mail) {
            return $mail->hasTo('user@example.com') && strlen($mail->otp) === 6;
        });
    }

    public function test_user_can_verify_otp(): void
    {
        DB::table('password_reset_otps')->insert([
            'email' => 'user@example.com',
            'otp' => '123456',
            'expires_at' => now()->addMinutes(15),
        ]);

        $response = $this->postJson('/api/verify-otp', [
            'email' => 'user@example.com',
            'otp' => '123456',
        ]);

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Verification code is valid.']);
    }

    public function test_user_can_reset_password_with_otp(): void
    {
        $user = User::factory()->create(['email' => 'user@example.com']);

        DB::table('password_reset_otps')->insert([
            'email' => 'user@example.com',
            'otp' => '123456',
            'expires_at' => now()->addMinutes(15),
        ]);

        $response = $this->postJson('/api/reset-password', [
            'email' => 'user@example.com',
            'otp' => '123456',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Password reset successfully.']);

        $this->assertTrue(Hash::check('newpassword123', $user->refresh()->password));
        $this->assertDatabaseMissing('password_reset_otps', ['email' => 'user@example.com']);
    }

    public function test_user_can_logout_via_api(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/logout');

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Successfully logged out.']);
    }
}
