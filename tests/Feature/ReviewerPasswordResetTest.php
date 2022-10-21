<?php

namespace Tests\Feature;

use App\Models\Reviewer;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ReviewerPasswordResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_reset_password_link_screen_can_be_rendered()
    {
        $response = $this->get('reviewer/forgot-password');

        $response->assertStatus(200);
    }

    public function test_reset_password_link_can_be_requested()
    {
        Notification::fake();

        $reviewer = Reviewer::factory()->create();

        $response = $this->post('reviewer/forgot-password', [
            'email' => $reviewer->email,
        ]);

        Notification::assertSentTo($reviewer, ResetPassword::class);
    }

    public function test_reset_password_screen_can_be_rendered()
    {
        Notification::fake();

        $reviewer = Reviewer::factory()->create();

        $response = $this->post('reviewer/forgot-password', [
            'email' => $reviewer->email,
        ]);

        Notification::assertSentTo($reviewer, ResetPassword::class, function ($notification) {
            $response = $this->get('reviewer/reset-password/'.$notification->token);

            $response->assertStatus(200);

            return true;
        });
    }

    public function test_password_can_be_reset_with_valid_token()
    {
        Notification::fake();

        $reviewer = Reviewer::factory()->create();

        $response = $this->post('reviewer/forgot-password', [
            'email' => $reviewer->email,
        ]);

        Notification::assertSentTo($reviewer, ResetPassword::class, function ($notification) use ($reviewer) {
            $response = $this->post('reviewer/reset-password', [
                'token' => $notification->token,
                'email' => $reviewer->email,
                'password' => 'password',
                'password_confirmation' => 'password',
            ]);

            $response->assertSessionHasNoErrors();

            return true;
        });
    }
}
