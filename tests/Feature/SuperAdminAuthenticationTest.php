<?php

namespace Tests\Feature;

use App\Models\SuperAdmin;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SuperAdminAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered()
    {
        $response = $this->get('super_admin/login');

        $response->assertStatus(200);
    }

    public function test_super_admins_can_authenticate_using_the_login_screen()
    {
        $super_admin = SuperAdmin::factory()->create();

        $response = $this->post('super_admin/login', [
            'email' => $super_admin->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated('super_admin');
        $response->assertRedirect(route('super_admin.dashboard'));
    }

    public function test_super_admins_can_not_authenticate_with_invalid_password()
    {
        $super_admin = SuperAdmin::factory()->create();

        $this->post('super_admin/login', [
            'email' => $super_admin->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest('super_admin');
    }
}
