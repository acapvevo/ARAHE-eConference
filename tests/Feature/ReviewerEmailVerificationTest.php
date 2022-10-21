<?php

namespace Tests\Feature;

use App\Models\Reviewer;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class ReviewerEmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_email_verification_screen_can_be_rendered()
    {
        $reviewer = Reviewer::factory()->create([
            'email_verified_at' => null,
        ]);

        $response = $this->actingAs($reviewer, 'reviewer')->get('reviewer/verify-email');

        $response->assertStatus(200);
    }

    public function test_email_can_be_verified()
    {
        Event::fake();

        $reviewer = Reviewer::factory()->create([
            'email_verified_at' => null,
        ]);

        $verificationUrl = URL::temporarySignedRoute(
            'reviewer.verification.verify',
            now()->addMinutes(60),
            ['id' => $reviewer->id, 'hash' => sha1($reviewer->email)]
        );

        $response = $this->actingAs($reviewer, 'reviewer')->get($verificationUrl);

        Event::assertDispatched(Verified::class);
        $this->assertTrue($reviewer->fresh()->hasVerifiedEmail());
        $response->assertRedirect(route('reviewer.dashboard').'?verified=1');
    }

    public function test_email_is_not_verified_with_invalid_hash()
    {
        $reviewer = Reviewer::factory()->create([
            'email_verified_at' => null,
        ]);

        $verificationUrl = URL::temporarySignedRoute(
            'reviewer.verification.verify',
            now()->addMinutes(60),
            ['id' => $reviewer->id, 'hash' => sha1('wrong-email')]
        );

        $this->actingAs($reviewer, 'reviewer')->get($verificationUrl);

        $this->assertFalse($reviewer->fresh()->hasVerifiedEmail());
    }
}
