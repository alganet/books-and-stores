<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Models\Book;

class BookController extends Controller
{
    /**
     * Store a newly created book in storage.
     */
    public function create(CreateBookRequest $request)
    {
        return response()->json(Book::create($request->validated())->fresh(), 201);
    }

    /**
     * Display the specified book.
     */
    public function show(Book $book)
    {
        return response()->json($book);
    }

    /**
     * Update the specified book in storage.
     */
    public function update(UpdateBookRequest $request, Book $book)
    {
        $book->update($request->validated());

        return response()->json($book);
    }

    /**
     * Remove the specified book from storage.
     */
    public function destroy(Book $book)
    {
        $book->delete();

        return response()->json(null, 204);
    }
}
