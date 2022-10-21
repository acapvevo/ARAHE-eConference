<?php

namespace Tests\Feature;

use App\Models\Reviewer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReviewerPasswordConfirmationTest extends TestCase
{
    use RefreshDatabase;

    public function test_confirm_password_screen_can_be_rendered()
    {
        $reviewer = Reviewer::factory()->create();

        $response = $this->actingAs($reviewer, 'reviewer')->get('reviewer/confirm-password');

        $response->assertStatus(200);
    }

    public function test_password_can_be_confirmed()
    {
        $reviewer = Reviewer::factory()->create();

        $response = $this->actingAs($reviewer, 'reviewer')->post('reviewer/confirm-password', [
            'password' => 'password',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();
    }

    public function test_password_is_not_confirmed_with_invalid_password()
    {
        $reviewer = Reviewer::factory()->create();

        $response = $this->actingAs($reviewer, 'reviewer')->post('reviewer/confirm-password', [
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors();
    }
}
