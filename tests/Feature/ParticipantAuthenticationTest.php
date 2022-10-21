<?php

namespace Tests\Feature;

use App\Models\Participant;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ParticipantAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered()
    {
        $response = $this->get('participant/login');

        $response->assertStatus(200);
    }

    public function test_participants_can_authenticate_using_the_login_screen()
    {
        $participant = Participant::factory()->create();

        $response = $this->post('participant/login', [
            'email' => $participant->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated('participant');
        $response->assertRedirect(route('participant.dashboard'));
    }

    public function test_participants_can_not_authenticate_with_invalid_password()
    {
        $participant = Participant::factory()->create();

        $this->post('participant/login', [
            'email' => $participant->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest('participant');
    }
}
