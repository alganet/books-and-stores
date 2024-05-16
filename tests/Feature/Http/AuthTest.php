<?php

namespace Tests\Feature\Http;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use App\Models\User;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::create([
            'name' => 'Test',
            'email' => 'test@test.com',
            'password' => '12345678'
        ]);
    }

    public function test_authentication_failure(): void
    {
        $response = $this->postJson(
            '/api/login',
            ['email' => 'test@test.com', 'password' => '99999999'],
        );

        $response->assertUnauthorized();
    }

    public function test_authentication_success(): void
    {
        $response = $this->postJson(
            '/api/login',
            ['email' => 'test@test.com', 'password' => '12345678'],
        );

        $response->assertOk();
    }


    public function test_logout(): void
    {
        Sanctum::actingAs($this->user);

        $response = $this->postJson('/api/logout');

        $response->assertOk();
    }

    public function test_authentication_guard(): void
    {
        $response = $this->postJson(
            '/api/books',
            ['name' => 'My Book']
        );

        $response->assertUnauthorized();
    }
}
