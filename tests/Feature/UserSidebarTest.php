<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserSidebarTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_dashboard_contains_profile_menu_link(): void
    {
        $user = User::factory()->create([
            'role' => 'User',
        ]);

        $response = $this->actingAs($user)->get(route('user.dashboard'));

        $response->assertOk();
        $response->assertSee(route('profile.edit', absolute: false));
    }
}

