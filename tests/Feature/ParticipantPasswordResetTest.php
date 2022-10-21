<?php

namespace Tests\Feature;

use App\Models\Participant;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ParticipantPasswordResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_reset_password_link_screen_can_be_rendered()
    {
        $response = $this->get('participant/forgot-password');

        $response->assertStatus(200);
    }

    public function test_reset_password_link_can_be_requested()
    {
        Notification::fake();

        $participant = Participant::factory()->create();

        $response = $this->post('participant/forgot-password', [
            'email' => $participant->email,
        ]);

        Notification::assertSentTo($participant, ResetPassword::class);
    }

    public function test_reset_password_screen_can_be_rendered()
    {
        Notification::fake();

        $participant = Participant::factory()->create();

        $response = $this->post('participant/forgot-password', [
            'email' => $participant->email,
        ]);

        Notification::assertSentTo($participant, ResetPassword::class, function ($notification) {
            $response = $this->get('participant/reset-password/'.$notification->token);

            $response->assertStatus(200);

            return true;
        });
    }

    public function test_password_can_be_reset_with_valid_token()
    {
        Notification::fake();

        $participant = Participant::factory()->create();

        $response = $this->post('participant/forgot-password', [
            'email' => $participant->email,
        ]);

        Notification::assertSentTo($participant, ResetPassword::class, function ($notification) use ($participant) {
            $response = $this->post('participant/reset-password', [
                'token' => $notification->token,
                'email' => $participant->email,
                'password' => 'password',
                'password_confirmation' => 'password',
            ]);

            $response->assertSessionHasNoErrors();

            return true;
        });
    }
}
