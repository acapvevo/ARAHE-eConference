<?php

namespace Tests\Feature;

use App\Models\SuperAdmin;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class SuperAdminEmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_email_verification_screen_can_be_rendered()
    {
        $super_admin = SuperAdmin::factory()->create([
            'email_verified_at' => null,
        ]);

        $response = $this->actingAs($super_admin, 'super_admin')->get('super_admin/verify-email');

        $response->assertStatus(200);
    }

    public function test_email_can_be_verified()
    {
        Event::fake();

        $super_admin = SuperAdmin::factory()->create([
            'email_verified_at' => null,
        ]);

        $verificationUrl = URL::temporarySignedRoute(
            'super_admin.verification.verify',
            now()->addMinutes(60),
            ['id' => $super_admin->id, 'hash' => sha1($super_admin->email)]
        );

        $response = $this->actingAs($super_admin, 'super_admin')->get($verificationUrl);

        Event::assertDispatched(Verified::class);
        $this->assertTrue($super_admin->fresh()->hasVerifiedEmail());
        $response->assertRedirect(route('super_admin.dashboard').'?verified=1');
    }

    public function test_email_is_not_verified_with_invalid_hash()
    {
        $super_admin = SuperAdmin::factory()->create([
            'email_verified_at' => null,
        ]);

        $verificationUrl = URL::temporarySignedRoute(
            'super_admin.verification.verify',
            now()->addMinutes(60),
            ['id' => $super_admin->id, 'hash' => sha1('wrong-email')]
        );

        $this->actingAs($super_admin, 'super_admin')->get($verificationUrl);

        $this->assertFalse($super_admin->fresh()->hasVerifiedEmail());
    }
}
