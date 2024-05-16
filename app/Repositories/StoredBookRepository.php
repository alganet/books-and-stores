<?php

namespace App\Repositories;

use App\Models\StoredBook;
use App\Models\Book;
use App\Models\Store;

class StoredBookRepository
{
    public function deleteOneByStoreAndBook(Store $store, Book $book): bool
    {
        return StoredBook::where([
            'store_id' => $store->getKey(),
            'book_id' => $book->getKey(),
        ])->get()->first()->delete();
    }
}
