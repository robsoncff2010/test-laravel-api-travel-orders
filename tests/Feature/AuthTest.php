<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_successfully()
    {
        $response = $this->postJson('/api/v1/register', [
            'name'                  => 'Teste Pessoa',
            'email'                 => 'teste@example.com',
            'password'              => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(201)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Usuário registrado com sucesso',
                 ]);
    }

    public function test_register_fails_if_email_already_exists()
    {
        User::factory()->create(['email' => 'teste@example.com']);

        $response = $this->postJson('/api/v1/register', [
            'name'                  => 'Outro',
            'email'                 => 'teste@example.com',
            'password'              => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['email']);
    }

    public function test_user_can_login_successfully()
    {
        $user = User::factory()->create([
            'email'    => 'robson@example.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->postJson('/api/v1/login', [
            'email'    => 'robson@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure(['success', 'message', 'token', 'user']);
    }

    public function test_login_fails_with_invalid_credentials()
    {
        $response = $this->postJson('/api/v1/login', [
            'email'    => 'robson@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(422)
                 ->assertJson([
                     'success'    => false,
                     'error_code' => 'INVALID_CREDENTIALS',
                 ]);
    }
}