<?php

namespace Tests\Feature;

use App\Models\SuperAdmin;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class SuperAdminPasswordResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_reset_password_link_screen_can_be_rendered()
    {
        $response = $this->get('super_admin/forgot-password');

        $response->assertStatus(200);
    }

    public function test_reset_password_link_can_be_requested()
    {
        Notification::fake();

        $super_admin = SuperAdmin::factory()->create();

        $response = $this->post('super_admin/forgot-password', [
            'email' => $super_admin->email,
        ]);

        Notification::assertSentTo($super_admin, ResetPassword::class);
    }

    public function test_reset_password_screen_can_be_rendered()
    {
        Notification::fake();

        $super_admin = SuperAdmin::factory()->create();

        $response = $this->post('super_admin/forgot-password', [
            'email' => $super_admin->email,
        ]);

        Notification::assertSentTo($super_admin, ResetPassword::class, function ($notification) {
            $response = $this->get('super_admin/reset-password/'.$notification->token);

            $response->assertStatus(200);

            return true;
        });
    }

    public function test_password_can_be_reset_with_valid_token()
    {
        Notification::fake();

        $super_admin = SuperAdmin::factory()->create();

        $response = $this->post('super_admin/forgot-password', [
            'email' => $super_admin->email,
        ]);

        Notification::assertSentTo($super_admin, ResetPassword::class, function ($notification) use ($super_admin) {
            $response = $this->post('super_admin/reset-password', [
                'token' => $notification->token,
                'email' => $super_admin->email,
                'password' => 'password',
                'password_confirmation' => 'password',
            ]);

            $response->assertSessionHasNoErrors();

            return true;
        });
    }
}
