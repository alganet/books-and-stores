<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateStoreRequest;
use App\Http\Requests\UpdateStoreRequest;
use App\Models\Store;

class StoreController extends Controller
{
    /**
     * Store a newly created store in storage (storage inception!).
     */
    public function create(CreateStoreRequest $request)
    {
        return response()->json(Store::create($request->validated())->fresh(), 201);
    }

    /**
     * Display the specified store.
     */
    public function show(Store $store)
    {
        return response()->json($store, );
    }

    /**
     * Update the specified store in storage.
     */
    public function update(UpdateStoreRequest $request, Store $store)
    {
        $store->update($request->validated());

        return response()->json($store);
    }

    /**
     * Remove the specified store from storage.
     */
    public function destroy(Store $store)
    {
        $store->delete();

        return response()->json(null, 204);
    }
}
