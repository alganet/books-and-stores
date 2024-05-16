<?php

namespace Tests\Feature\Http;

use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\StoredBook;
use App\Models\Book;
use App\Models\Store;
use Laravel\Sanctum\Sanctum;

class StoredBookTest extends TestCase
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

    public function test_associate_book(): void
    {
        $book = Book::factory()->create();
        $store = Store::factory()->create();

        $response = $this->postJson(
            '/api/stored-books',
            ['book_id' => $book->getKey(), 'store_id' => $store->getKey()]
        );

        $response
            ->assertSuccessful()
            ->assertJson(fn (AssertableJson $json) => $json
                ->hasAll(['id', 'store_id', 'book_id', 'created_at', 'updated_at'])
                ->where('id', 1)
                ->where('store_id', $store->getKey())
                ->where('book_id', $book->getKey()));
    }

    public function test_retrieve_association(): void
    {
        $storedBook = StoredBook::factory()->create();

        $response = $this->getJson("/api/stored-books/{$storedBook->getKey()}");

        $response
            ->assertSuccessful()
            ->assertJson(fn (AssertableJson $json) => $json
                ->hasAll(['id', 'book_id', 'store_id', 'created_at', 'updated_at'])
                ->where('id', $storedBook->getKey())
                ->where('book_id', $storedBook->book->getKey())
                ->where('store_id', $storedBook->store->getKey()));
    }

    public function test_delete_association(): void
    {
        $storedBook = StoredBook::factory()->create();

        $response = $this->delete("/api/stored-books/{$storedBook->getKey()}");

        $response->assertSuccessful();
        $this->assertNull(StoredBook::find($storedBook->getKey()));
    }

    public function test_delete_association_by_foreign_store(): void
    {
        $storedBook = StoredBook::factory()->create();

        $response = $this->delete(
            "/api/stores/{$storedBook->store->getKey()}/books/{$storedBook->book->getKey()}"
        );

        $response->assertSuccessful();
        $this->assertNull(StoredBook::find($storedBook->getKey()));
    }
}
