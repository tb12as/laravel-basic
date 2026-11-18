<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Product::orderByDesc('id')->get());
    }

    public function show(Product $product)
    {
        return $product;
    }

    public function store(Request $request): JsonResponse
    {
        $fields = $request->validate([
            'name' => 'required|max:255',
            'price' => 'required|numeric|min:1',
            'stock' => 'required|numeric|min:1',
        ]);

        return response()->json(Product::create($fields), 201);
    }
}
