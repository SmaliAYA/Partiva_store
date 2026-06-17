<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Cloudinary\Cloudinary;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();

        return response()->json([
            'data'    => $products,
            'message' => 'Products retrieved successfully',
            'count'   => $products->count(),
        ], 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'description'   => 'nullable|string',
            'price'         => 'required|numeric',
            'stock'         => 'required|integer|min:0',
            'category_name' => 'nullable|string|max:255',
            'image'         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_active'     => 'boolean',
        ]);

        $imageUrl = null;

        if ($request->hasFile('image')) {
            $cloudinary = new Cloudinary([
                'cloud' => [
                    'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
                    'api_key'    => env('CLOUDINARY_API_KEY'),
                    'api_secret' => env('CLOUDINARY_API_SECRET'),
                ],
            ]);

            $uploaded = $cloudinary->uploadApi()->upload(
                $request->file('image')->getRealPath(),
                ['folder' => 'partiva/products']
            );

            $imageUrl = $uploaded['secure_url'];
        }

        $product = Product::create([
            'name'          => $validated['name'],
            'slug'          => Str::slug($validated['name']) . '-' . time(),
            'description'   => $validated['description'] ?? null,
            'price'         => $validated['price'],
            'stock'         => $validated['stock'],
            'image'         => $imageUrl,
            'category_name' => $validated['category_name'] ?? null,
            'is_active'     => $validated['is_active'] ?? true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Product created successfully',
            'data'    => $product,
        ], 201);
    }

    public function show(Product $product)
    {
        return response()->json([
            'data'    => $product,
            'message' => 'Product retrieved successfully',
        ], 200);
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name'          => 'sometimes|string|max:255',
            'slug'          => 'sometimes|string|max:255|unique:products,slug,' . $product->id,
            'description'   => 'sometimes|nullable|string',
            'price'         => 'sometimes|numeric',
            'stock'         => 'sometimes|integer',
            'image'         => 'sometimes|nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'category_name' => 'sometimes|nullable|string|max:255',
            'is_active'     => 'sometimes|boolean',
        ]);

        if ($request->hasFile('image')) {
            $cloudinary = new Cloudinary([
                'cloud' => [
                    'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
                    'api_key'    => env('CLOUDINARY_API_KEY'),
                    'api_secret' => env('CLOUDINARY_API_SECRET'),
                ],
            ]);

            $uploaded = $cloudinary->uploadApi()->upload(
                $request->file('image')->getRealPath(),
                ['folder' => 'partiva/products']
            );

            $validated['image'] = $uploaded['secure_url'];
        }

        $product->update($validated);

        return response()->json([
            'data'    => $product->fresh(),
            'message' => 'Product updated successfully',
        ], 200);
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return response()->json([
            'message' => 'Product deleted successfully',
        ], 200);
    }
}