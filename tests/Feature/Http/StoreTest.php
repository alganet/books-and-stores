<?php

namespace Tests\Feature\Http;

use PHPUnit\Framework\Attributes\DataProvider;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Store;
use Laravel\Sanctum\Sanctum;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::create([
            'name' => 'Test',
            'email' => 'test@test.com',
            'password' => '12345678'
        ]);

        Sanctum::actingAs($this->user);
    }

    public function test_create_store(): void
    {
        $response = $this->postJson(
            '/api/stores',
            ['name' => 'My Store']
        );

        $response
            ->assertSuccessful()
            ->assertJson(fn (AssertableJson $json) => $json
                ->hasAll(['id', 'name', 'address', 'active', 'created_at', 'updated_at'])
                ->where('id', 1)
                ->where('name', 'My Store'));
    }

    #[DataProvider('invalidInputs')]
    public function test_create_store_validation(array $input, string $expectedMessage): void
    {
        $response = $this->postJson(
            '/api/stores',
            $input
        );

        $response
            ->assertUnprocessable()
            ->assertJson(fn (AssertableJson $json) => $json
                ->missingAll(['id', 'name', 'address', 'active', 'created_at', 'updated_at'])
                ->has('errors')
                ->where('message', $expectedMessage));
    }

    public static function invalidInputs(): array
    {
        return [
            [['XXXX' => 'My Store'], "The name field is required."],
            [['name' => 'My Store', 'active' => 'My Store'], "The active field must be true or false."],
        ];
    }

    public function test_retrieve_store(): void
    {
        $store = Store::factory()->create();

        $response = $this->getJson("/api/stores/{$store->getKey()}");

        $response
            ->assertSuccessful()
            ->assertJson(fn (AssertableJson $json) => $json
                ->hasAll(['id', 'name', 'address', 'active', 'created_at', 'updated_at'])
                ->where('id', $store->getKey())
                ->where('name', $store->name)
                ->where('address', $store->address));
    }

    public function test_update_store(): void
    {
        $store = Store::factory()->create();

        $response = $this->putJson(
            "/api/stores/{$store->getKey()}",
            ['name' => 'An Updated Store']
        );

        $store->refresh();

        $this->assertEquals('An Updated Store', $store->name);
        $response
            ->assertSuccessful()
            ->assertJson(fn (AssertableJson $json) => $json
                ->hasAll(['id', 'name', 'address', 'active', 'created_at', 'updated_at'])
                ->where('id', $store->getKey())
                ->where('name', 'An Updated Store')
                ->where('address', $store->address));
    }

    public function test_delete_store(): void
    {
        $store = Store::factory()->create();

        $response = $this->delete("/api/stores/{$store->getKey()}");

        $response->assertSuccessful();
        $this->assertNull(Store::find($store->getKey()));
    }
}
