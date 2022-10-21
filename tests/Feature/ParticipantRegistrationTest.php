<?php

namespace Tests\Feature;

use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ParticipantRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered()
    {
        $response = $this->get('participant/register');

        $response->assertStatus(200);
    }

    public function test_new_participants_can_register()
    {
        $response = $this->post('participant/register', [
            'name' => 'Test Participant',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertAuthenticated('participant');
        $response->assertRedirect(route('participant.dashboard'));
    }
}
