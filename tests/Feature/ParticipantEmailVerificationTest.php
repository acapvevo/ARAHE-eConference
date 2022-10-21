<?php

namespace Tests\Feature;

use App\Models\Participant;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class ParticipantEmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_email_verification_screen_can_be_rendered()
    {
        $participant = Participant::factory()->create([
            'email_verified_at' => null,
        ]);

        $response = $this->actingAs($participant, 'participant')->get('participant/verify-email');

        $response->assertStatus(200);
    }

    public function test_email_can_be_verified()
    {
        Event::fake();

        $participant = Participant::factory()->create([
            'email_verified_at' => null,
        ]);

        $verificationUrl = URL::temporarySignedRoute(
            'participant.verification.verify',
            now()->addMinutes(60),
            ['id' => $participant->id, 'hash' => sha1($participant->email)]
        );

        $response = $this->actingAs($participant, 'participant')->get($verificationUrl);

        Event::assertDispatched(Verified::class);
        $this->assertTrue($participant->fresh()->hasVerifiedEmail());
        $response->assertRedirect(route('participant.dashboard').'?verified=1');
    }

    public function test_email_is_not_verified_with_invalid_hash()
    {
        $participant = Participant::factory()->create([
            'email_verified_at' => null,
        ]);

        $verificationUrl = URL::temporarySignedRoute(
            'participant.verification.verify',
            now()->addMinutes(60),
            ['id' => $participant->id, 'hash' => sha1('wrong-email')]
        );

        $this->actingAs($participant, 'participant')->get($verificationUrl);

        $this->assertFalse($participant->fresh()->hasVerifiedEmail());
    }
}
