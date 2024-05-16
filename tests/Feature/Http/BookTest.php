<?php

namespace Tests\Feature\Http;

use PHPUnit\Framework\Attributes\DataProvider;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use App\Models\Book;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

class BookTest extends TestCase
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

    public function test_create_book(): void
    {
        $response = $this->postJson(
            '/api/books',
            ['name' => 'My Book']
        );

        $response
            ->assertSuccessful()
            ->assertJson(fn (AssertableJson $json) => $json
                ->hasAll(['id', 'name', 'isbn', 'value', 'created_at', 'updated_at'])
                ->where('id', 1)
                ->where('name', 'My Book'));
    }

    #[DataProvider('invalidInputs')]
    public function test_create_book_validation(array $input, string $expectedMessage): void
    {
        $response = $this->postJson(
            '/api/books',
            $input
        );

        $response
            ->assertUnprocessable()
            ->assertJson(fn (AssertableJson $json) => $json
                ->missingAll(['id', 'name', 'isbn', 'value', 'created_at', 'updated_at'])
                ->has('errors')
                ->where('message', $expectedMessage));
    }

    public static function invalidInputs(): array
    {
        return [
            [['XXXX' => 'My Book'], "The name field is required."],
            [['name' => 'My Book', 'isbn' => 'My Book'], "The isbn field must be a number."],
            [['name' => 'My Book', 'isbn' => '12456', 'value' => 'ZZZ'], "The value field must have 2 decimal places."],
        ];
    }

    public function test_retrieve_book(): void
    {
        $book = Book::factory()->create();

        $response = $this->getJson("/api/books/{$book->getKey()}");

        $response
            ->assertSuccessful()
            ->assertJson(fn (AssertableJson $json) => $json
                ->hasAll(['id', 'name', 'isbn', 'value', 'created_at', 'updated_at'])
                ->where('id', $book->getKey())
                ->where('name', $book->name)
                ->where('isbn', (string) $book->isbn));
    }

    public function test_update_book(): void
    {
        $book = Book::factory()->create();

        $response = $this->putJson(
            "/api/books/{$book->getKey()}",
            ['name' => 'An Updated Book']
        );

        $book->refresh();

        $this->assertEquals('An Updated Book', $book->name);
        $response
            ->assertSuccessful()
            ->assertJson(fn (AssertableJson $json) => $json
                ->hasAll(['id', 'name', 'isbn', 'value', 'created_at', 'updated_at'])
                ->where('id', $book->getKey())
                ->where('name', 'An Updated Book')
                ->where('isbn', (string) $book->isbn));
    }

    public function test_delete_book(): void
    {
        $book = Book::factory()->create();

        $response = $this->delete("/api/books/{$book->getKey()}");

        $response->assertSuccessful();
        $this->assertNull(book::find($book->getKey()));
    }
}
