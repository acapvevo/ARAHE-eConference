<?php

namespace Tests\Feature;

use App\Models\SuperAdmin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SuperAdminPasswordConfirmationTest extends TestCase
{
    use RefreshDatabase;

    public function test_confirm_password_screen_can_be_rendered()
    {
        $super_admin = SuperAdmin::factory()->create();

        $response = $this->actingAs($super_admin, 'super_admin')->get('super_admin/confirm-password');

        $response->assertStatus(200);
    }

    public function test_password_can_be_confirmed()
    {
        $super_admin = SuperAdmin::factory()->create();

        $response = $this->actingAs($super_admin, 'super_admin')->post('super_admin/confirm-password', [
            'password' => 'password',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();
    }

    public function test_password_is_not_confirmed_with_invalid_password()
    {
        $super_admin = SuperAdmin::factory()->create();

        $response = $this->actingAs($super_admin, 'super_admin')->post('super_admin/confirm-password', [
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors();
    }
}
