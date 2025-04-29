<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreShopItemRequest; // Buat request baru untuk validasi
use App\Models\ShopItem; // Import model ShopItem
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShopListController extends Controller // Ganti nama controller
{
    public function index(): JsonResponse
    {
        $shopItems = Auth::user()->shopItems()->get(); // Gunakan relasi shopItems
        return response()->json($shopItems);
    }

    public function store(StoreShopItemRequest $request): JsonResponse // Gunakan request baru
    {
        $shopItem = Auth::user()->shopItems()->create($request->validated());
        return response()->json($shopItem, 201);
    }

    public function destroy(ShopItem $shopItem): JsonResponse // Gunakan model ShopItem
    {
        if ($shopItem->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $shopItem->delete();
        return response()->json(null, 204);
    }

    public function search(Request $request): JsonResponse
    {
        $request->validate(['query' => 'required|string']);
        $query = $request->input('query');

        $results = Auth::user()->shopItems() // Gunakan relasi shopItems
            ->where('title', 'like', "%{$query}%")
            ->get(); // Hanya cari berdasarkan title

        return response()->json($results);
    }
}
