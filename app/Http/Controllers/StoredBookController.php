<?php

namespace App\Http\Controllers;

use App\Http\Requests\AssociateBookRequest;
use App\Models\StoredBook;
use App\Models\Book;
use App\Models\Store;
use App\Repositories\StoredBookRepository;

class StoredBookController extends Controller
{
    public function __construct(
        protected StoredBookRepository $storedBookRepository,
    ) {
    }

    /**
     * Store a newly created book/store association in storage.
     */
    public function create(AssociateBookRequest $request)
    {
        return response()->json(StoredBook::create($request->validated())->fresh(), 201);
    }

    /**
     * Display the specified book/store association.
     */
    public function show(StoredBook $storedBook)
    {
        return response()->json($storedBook);
    }

    /**
     * Remove the specified book/store association from storage.
     */
    public function destroy(StoredBook $storedBook)
    {
        $storedBook->delete();

        return response()->json(null, 204);
    }

    /**
     * Remove the specified book/store association from storage.
     */
    public function dissociateBook(Store $store, Book $book)
    {
        if ($this->storedBookRepository->deleteOneByStoreAndBook($store, $book)) {
            return response()->json(null, 204);
        }

        return response()->json(null, 400);
    }
}
