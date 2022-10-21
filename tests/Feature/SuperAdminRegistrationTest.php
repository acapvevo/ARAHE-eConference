<?php

namespace Tests\Feature;

use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SuperAdminRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered()
    {
        $response = $this->get('super_admin/register');

        $response->assertStatus(200);
    }

    public function test_new_super_admins_can_register()
    {
        $response = $this->post('super_admin/register', [
            'name' => 'Test SuperAdmin',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertAuthenticated('super_admin');
        $response->assertRedirect(route('super_admin.dashboard'));
    }
}
