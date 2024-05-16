<?php

use Illuminate\Support\Facades\Route;

Route::post('login', [App\Http\Controllers\AuthController::class,'login']);

Route::middleware(['auth:sanctum'])->group(function () {

    Route::post('logout', [App\Http\Controllers\AuthController::class,'logout']);

    Route::controller(App\Http\Controllers\BookController::class)->group(function () {
        Route::post('books', 'create');
        Route::get('books/{book}', 'show');
        Route::put('books/{book}', 'update');
        Route::delete('books/{book}', 'destroy');
    });

    Route::controller(App\Http\Controllers\StoreController::class)->group(function () {
        Route::post('stores', 'create');
        Route::get('stores/{store}', 'show');
        Route::put('stores/{store}', 'update');
        Route::delete('stores/{store}', 'destroy');
    });

    Route::controller(App\Http\Controllers\StoredBookController::class)->group(function () {
        Route::post('stored-books', 'create');
        Route::get('stored-books/{storedBook}', 'show');
        Route::delete('stored-books/{storedBook}', 'destroy');
        Route::delete('stores/{store}/books/{book}', 'dissociateBook');
    });
});
