<?php

namespace Tests\Feature;

use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class AdminAuthControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function admin_bisa_registrasi()
    {
        $response = $this->post('/admin/register', [
            'username' => 'admin_rio',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect('/admin/login');
        $this->assertDatabaseHas('admin', ['username' => 'admin_rio']);
    }

    #[Test]
    public function admin_bisa_login_dengan_kredensial_valid()
    {
        $admin = Admin::create([
            'username' => 'admin_rio',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->post('/admin/login', [
            'username' => 'admin_rio',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/admin');
        $this->assertEquals($admin->id_admin, session('admin_id'));
        $this->assertEquals($admin->username, session('admin_username'));
    }
}
