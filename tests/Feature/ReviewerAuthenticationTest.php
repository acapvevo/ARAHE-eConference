<?php

namespace Tests\Feature;

use App\Models\Reviewer;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReviewerAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered()
    {
        $response = $this->get('reviewer/login');

        $response->assertStatus(200);
    }

    public function test_reviewers_can_authenticate_using_the_login_screen()
    {
        $reviewer = Reviewer::factory()->create();

        $response = $this->post('reviewer/login', [
            'email' => $reviewer->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated('reviewer');
        $response->assertRedirect(route('reviewer.dashboard'));
    }

    public function test_reviewers_can_not_authenticate_with_invalid_password()
    {
        $reviewer = Reviewer::factory()->create();

        $this->post('reviewer/login', [
            'email' => $reviewer->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest('reviewer');
    }
}
