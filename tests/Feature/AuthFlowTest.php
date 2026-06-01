<?php

namespace Tests\Feature;

use App\Enums\RoleCode;
use App\Models\Branch;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\BranchSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;
use Tests\TestCase;

class AuthFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login_fetch_profile_and_logout(): void
    {
        $user = $this->createActiveUser('phase-one@example.com', 'correct-password');

        $loginResponse = $this->postJson('/api/v1/login', [
            'email' => 'phase-one@example.com',
            'password' => 'correct-password',
            'device_name' => 'phase-one-test',
        ]);

        $loginResponse
            ->assertOk()
            ->assertJsonPath('message', 'Login successful.')
            ->assertJsonPath('data.email', $user->email)
            ->assertJsonStructure(['token', 'data' => ['id', 'email']]);

        $token = $loginResponse->json('token');

        $this->withToken($token)
            ->getJson('/api/v1/me')
            ->assertOk()
            ->assertJsonPath('data.email', $user->email);

        $this->assertSame(1, PersonalAccessToken::count());

        $this->withToken($token)
            ->postJson('/api/v1/logout')
            ->assertOk()
            ->assertJsonPath('message', 'Logout successful.');

        $this->assertSame(0, PersonalAccessToken::count());
    }

    public function test_inactive_user_cannot_login(): void
    {
        $this->createActiveUser('inactive@example.com', 'correct-password', false);

        $this->postJson('/api/v1/login', [
            'email' => 'inactive@example.com',
            'password' => 'correct-password',
        ])
            ->assertUnprocessable()
            ->assertJsonPath('code', 'VALIDATION_ERROR')
            ->assertJsonValidationErrors(['email']);
    }

    private function createActiveUser(string $email, string $password, bool $active = true): User
    {
        $this->seed([RoleSeeder::class, BranchSeeder::class]);

        $user = User::create([
            'branch_id' => Branch::where('code', 'HQ')->value('id'),
            'name' => 'Phase One Tester',
            'email' => $email,
            'password' => Hash::make($password),
            'is_active' => $active,
        ]);

        $role = Role::where('code', RoleCode::SUPER_ADMIN->value)->firstOrFail();
        $user->roles()->sync([$role->id]);

        return $user->refresh();
    }
}
