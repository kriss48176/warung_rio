<?php

namespace Tests\Feature;

use App\Models\Pelanggan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PelangganAuthControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function pelanggan_bisa_registrasi()
    {
        $response = $this->post('/pelanggan/register', [
            'name' => 'Budi Pelanggan',
            'email' => 'budi@gmail.com',
            'phone' => '08123456789',
            'address' => 'Jl. Mawar No. 123',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect('/pelanggan/login');
        $this->assertDatabaseHas('pelanggans', ['email' => 'budi@gmail.com']);
    }

    /** @test */
    public function pelanggan_bisa_login_dengan_kredensial_valid()
    {
        $pelanggan = Pelanggan::create([
            'name' => 'Budi Pelanggan',
            'email' => 'budi@gmail.com',
            'phone' => '08123456789',
            'address' => 'Jl. Mawar No. 123',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->post('/pelanggan/login', [
            'email' => 'budi@gmail.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/');
        $this->assertEquals($pelanggan->id, session('pelanggan_id'));
        $this->assertEquals($pelanggan->name, session('pelanggan_nama'));
    }
}