<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Notifications\AktivasiAkunNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class EmailActivationNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_activation_sends_email_notification(): void
    {
        Notification::fake();

        $admin = User::factory()->create([
            'role' => 'Admin',
            'status_user' => 'Teraktivasi',
        ]);

        $user = User::factory()->create([
            'role' => 'User',
            'status_user' => 'Belum Teraktivasi',
        ]);

        $response = $this
            ->actingAs($admin)
            ->from(route('user.not.active', absolute: false))
            ->patch(route('user.activate', $user, absolute: false));

        $response->assertRedirect(route('user.not.active', absolute: false));

        $this->assertSame('Teraktivasi', $user->refresh()->status_user);

        Notification::assertSentTo($user, AktivasiAkunNotification::class);
    }
}

